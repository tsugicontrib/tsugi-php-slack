<?php
require_once "config.php";

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




/*
// Handle your POST data here...
if ( isset($_POST['guess'])) {
    if ( !is_numeric($_POST['guess'])) {
        $_SESSION['error'] = "Guess must be numeric";
        header('Location: '.addSession('index.php'));
        return;
    }

    $PDOX->queryDie("INSERT INTO {$p}tsugi_sample_module
        (link_id, user_id, guess, updated_at)
        VALUES ( :LI, :UI, :GUESS, NOW() )
        ON DUPLICATE KEY UPDATE guess=:GUESS, updated_at = NOW()",
        array(
            ':LI' => $LINK->id,
            ':UI' => $USER->id,
            ':GUESS' => $_POST["guess"]
        )
    );

    if ( $_POST['guess'] == 42 ) {
        $_SESSION['success'] = "Nice work";
    } else {
        $_SESSION['error'] = "Please try again";
    }
    header('Location: '.addSession('index.php'));
    return;
}

// Retrieve the old data
$row = $PDOX->rowDie("SELECT guess FROM {$p}tsugi_sample_module
    WHERE user_id = :UI",
    array(':UI' => $USER->id)
);
$oldguess = $row ? $row['guess'] : '';
*/

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
    SettingsForm::text('subdomain',__('The name of the slack channel https://subdomain.slack.com/.'));
    SettingsForm::text('token',__('The administrative token for the above slack channel.'));
    SettingsForm::done();
    SettingsForm::end();
}

if ( strlen($token) < 1 || strlen($subdomain) < 1 ) {
    echo('<br clear="all"><p>This channel is not yet configured</p>'."\n");
    $OUTPUT->footer();
    return;
}

?>
        <div style="text-align: center; margin-top: 75px">
            <div>
                <img src="slack.svg" style="width: 150px; height: 150px;" />
            </div>
            <h2 style="font-family: 'Roboto', sans-serif; color: #ffffff">Join <?= $subdomain ?> on Slack!</h2>
            
            <?php
                $showform = false;
                $error = false;
                if (isset($_POST['first'])){
                    if (strlen($_POST['first']) > 1 && strlen($_POST['last']) > 1 && strlen($_POST['mail']) > 5){
                        sendForm();
                    } else {
                        $showform = true;
                        $error = true;
                    }
                } else {
                    $showform = true;
                }
            
            if ($showform){
                if ($error){
            ?>
            
            <p style="font-family: 'Roboto', sans-serif; color: #9d3d3d">
                Please fill in all fields
            </p>
            
            <?php
                    }
                    
                showForm();
                }
            ?>
        </div>

<?php
    
    function sendForm(){
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
                $reply=json_decode($replyRaw,true);
                if($reply['ok']==false) {
                        echo '<p style="font-family: \'Roboto\', sans-serif; color: #9d3d3d">';
                        echo 'Something went wrong, try again!';
                        echo '</p>';
                        showForm();
                }
                else {
                        echo '<p style="font-family: \'Roboto\', sans-serif; color: #719E6F">';
                        echo 'Invited successfully. Check your email. It should arrive within a couple minutes';
                        echo '</p>';
                }
    
        // close connection
                curl_close($ch);        
    }
    
    function showForm(){
        
        ?>
        
            <form method="post">
                <p style="font-family: 'Roboto', sans-serif; color: #ffffff">
                    First Name
                </p>
                
                <input type="text" name="first" style="width: 250px; " <?php echo strlen($_POST['first']) > 0 ? 'value="'.$_POST['first'].'"' : ''; ?> />
                
                <p style="font-family: 'Roboto', sans-serif; color: #ffffff">
                    Last Name
                </p>
                <input type="text" name="last" style="width: 250px; " <?php echo strlen($_POST['last']) > 0 ? 'value="'.$_POST['last'].'"' : ''; ?> />
                <p style="font-family: 'Roboto', sans-serif; color: #ffffff">
                    Email address
                </p>
                <input type="text" name="mail" style="width: 250px; " <?php echo strlen($_POST['mail']) > 0 ? 'value="'.$_POST['mail'].'"' : ''; ?> />
                <p>
                    <input type="submit" value="Sign me up!" />
                </p>
            </form>
            
        <?php       
        
    }
?>
<form method="post">
Pick a number:
<input type="text" name="guess" value="<?= $oldguess ?>"><br/>
<input type="submit" name="send" value="Guess">
</form>
<?php

echo("<pre>Global Tsugi Objects:\n\n");
var_dump($USER);
var_dump($CONTEXT);
var_dump($LINK);

echo("\n<hr/>\n");
echo("Session data (low level):\n");
echo($OUTPUT->safe_var_dump($_SESSION));

$OUTPUT->footerStart();
?>
<script>
// You might put some JavaScript here
</script>
<?php
$OUTPUT->footerEnd();

