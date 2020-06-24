<?php

include($_SERVER['DOCUMENT_ROOT'] . "/inc/main.php");
if (isset($_SESSION['user'])) {
    if (isset($_GET['domain'])) {
        if (!empty($_GET['newdomain'])) {
            $newdomain = $_GET['newdomain'];
        } else {
            exec(VESTA_CMD . "v-list-user " . $user . " json", $outputi, $return_vari);
            $datai = json_decode(implode('', $outputi), true);
            $dati  = array_reverse($datao, true);
            $email = $dati["$user"]['CONTACT'];
        }
        $domain = $_GET['domain'];
        if (isset($_GET['without'])) {
            $command='/usr/bin/sudo /usr/local/vesta/bin/v-sam-create-wp "'.$domain.'" "'.$newdomain.'" "'.$user.'" "1"    2>&1';
        } else {
            $command='/usr/bin/sudo /usr/local/vesta/bin/v-sam-create-wp "'.$domain.'" "'.$newdomain.'" "'.$user.'"   2>&1'; 
        }
        exec ($command, $output, $return_vari);
        $ddata  = implode('<br/>', $output);
        $ot = explode('~~~~', $ddata);
        if (!empty($ot['1'])) {
            echo '<div class="panel panel-info">
                        <div class="panel-heading">Install Detail</div>
                        <div class="panel-body">';
            print_r($ot['1']);
            $file = '/home/'.$newdomain.'.perekley.log';
            file_put_contents($file, $ot['1']);
            echo      '</div>
                  </div>';
            ob_flush();
            flush();
        }
    } elseif (isset($_GET['users']) ) {
         exec( "/usr/bin/sudo /usr/local/vesta/bin/v-sam-create-wp 1 2 3 2 2>&1", $outputu, $return_varu);
         echo (implode(' ', $outputu));
    }
} else {
    echo '<h2>your login expire... please re login</h2>';
    ob_flush();
    flush();
}
?>
