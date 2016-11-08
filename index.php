<?php
require_once "../config.php";

use \Tsugi\Core\LTIX;
use \Tsugi\Core\Settings;
use \Tsugi\UI\SettingsForm;

// Retrieve the launch data if present
$LTI = LTIX::requireData();
$p = $CFG->dbprefix;
$displayname = $USER->displayname;

if ( SettingsForm::handleSettingsPost() ) {
    header( 'Location: '.addSession('index.php') ) ;
    return;
}

$subdomain = Settings::linkGet('subdomain', '');
$token = Settings::linkGet('token', '');


    
if ( isset($_POST['mail']) && isset($_POST['first']) && isset($_POST['last']) ) {
    $email = $_POST['mail'];
    $first = $_POST['first'];
    $last = $_POST['last'];
        
    $slackInviteUrl='https://'.$subdomain .'.slack.com/api/users.admin.invite?t='.time();
    $fields = array(
        'email' => urlencode($email),
        'first_name' => urlencode($first),
        'token' => $token,
        'set_active' => urlencode('true'),
        '_attempts' => '1'
    );
    
    // url-ify the data for the POST
    $fields_string='';
    foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
    rtrim($fields_string, '&');
    
    // open connection
    $ch = curl_init();
    
    // set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $slackInviteUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch,CURLOPT_POST, count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

    // exec
    $replyRaw = curl_exec($ch);
    error_log($replyRaw);
    curl_close($ch);        
    $reply=json_decode($replyRaw,true);

    if($reply == null || (!isset($reply['ok'])) || $reply['ok']==false) {
        $msg = "Something went wrong";
        if ( isset($reply['error']) ) $msg .= ': '.$reply['error'];
        $_SESSION['error'] = $msg;
    } else {
        $PDOX->queryDie("INSERT INTO {$p}tsugi_slack
            (link_id, user_id, created_at, updated_at)
            VALUES ( :LI, :UI, NOW(), NOW() )
            ON DUPLICATE KEY UPDATE updated_at = NOW()",
            array(
                ':LI' => $LINK->id,
                ':UI' => $USER->id
            )
        );

        $_SESSION['success'] = 'Invited successfully. Check your email. It should arrive within a couple minutes';
    }
    header('Location: '.addSession('index.php'));
    return;
    
}

// Retrieve the old data
$row = $PDOX->rowDie("SELECT updated_at FROM {$p}tsugi_slack
    WHERE user_id = :UI AND link_id = :LI",
    array(
        ':LI' => $LINK->id,
        ':UI' => $USER->id
    )
);
$invited = $row !== false;

// Start of the output
$OUTPUT->header();
?>
<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
<?php
$OUTPUT->bodyStart();
if ( $USER->instructor ) {
    SettingsForm::button(true);
}
$OUTPUT->flashMessages();
if ( $USER->instructor ) {
    SettingsForm::start();
    SettingsForm::text('subdomain',__('Enter the name (slackname) or your channel https://slackchan.slack.com/".'));
    SettingsForm::text('token',__('The administrative token for the above slack channel.'));
    SettingsForm::done();
    SettingsForm::end();
}

if ( strlen($token) < 1 || strlen($subdomain) < 1 ) {
    echo('<br clear="all"><p>This Slack channel is not yet configured.</p>'."\n");
    $OUTPUT->footer();
    return;
}

?>
        <div style="text-align: center; margin-top: 15px">
            <div>
                <img src="slack.svg" style="width: 100px; height: 100px;" />
            </div>
            <h2 style="font-family: 'Roboto', sans-serif; color: #888888">
            <?= ($invited ? 'Launch' : 'Join') ?>
            <a href="https://<?= $subdomain ?>.slack.com" id="toslack"><?= $subdomain ?></a>
            on Slack!</h2>
            
            <p style="font-family: 'Roboto', sans-serif; color: #9d3d3d">
                If you are already in the Slack, use the link above to launch the channel.<br/> To get an invitation, please fill in all fields below and request an invitation.
            </p>
            
            <form method="post">
                <p style="font-family: 'Roboto', sans-serif; color: #888888">
                    First Name
                </p>
                
                <input type="text" name="first" style="width: 250px;" value="<?= $USER->firstname ?>">
                
                <p style="font-family: 'Roboto', sans-serif; color: #888888">
                    Last Name
                </p>
                <input type="text" name="last" style="width: 250px;" value="<?= $USER->lastname ?>">
                <p style="font-family: 'Roboto', sans-serif; color: #888888">
                    Email address
                </p>
                <input type="text" name="mail" style="width: 250px; " value="<?= $USER->email ?>">
                <p>
                    <input type="submit" value="<?= ( $invited ? 'Resend Invitation' : 'Send invitation!' ) ?>" />
                </p>
            </form>
            
        </div>

<?php
/*
echo("<pre>Global Tsugi Objects:\n\n");
var_dump($USER);
var_dump($CONTEXT);
var_dump($LINK);

echo("\n<hr/>\n");
echo("Session data (low level):\n");
echo($OUTPUT->safe_var_dump($_SESSION));
*/

$OUTPUT->footerStart();
?>
<script>

try {
    if( window.self !== window.top ) {
        $('#toslack').attr('target','_blank');
    }
} catch (e) {
    $('#toslack').attr('target','_blank');
}
</script>
<?php
$OUTPUT->footerEnd();

