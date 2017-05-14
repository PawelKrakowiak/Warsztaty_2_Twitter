<?php

function echoCenter($string, $fail=0){
    echo "<div class='container text-center'>
            <div class='row'>
                <div class='col-md-2'></div>
                <div class='col-md-8'>
                    <p><h3 ";
    if($fail){
        echo "class='fail'";
    }    
    echo">$string</h3></p>
                </div>
                <div class='col-md-2'></div>
            </div>
        </div>";
}

