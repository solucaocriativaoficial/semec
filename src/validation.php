<?php
session_start();

$verify_action = $_POST["verify_action"];
$cpf = $_POST["cpf"];
$password_acess =  md5($_POST['password_acess']);

if(isset($_POST))
{
    include_once("class". DIRECTORY_SEPARATOR ."LoadClass.php");
    $connection = new ConnectionDatabase();

    if($verify_action == "login")
    {
 
        $select = $connection->find(
            "SELECT id, name_person FROM person where cpf='$cpf' AND password_acess=:password_acess",
            array(
                ":password_acess"=>$password_acess
        ));

        if(count($select))
        {
            $_SESSION["id_user"] = $select[0]["id"];
            $_SESSION["name_person"] = $select[0]["name_person"];
            header("Location: ./pages/home");
        }
        else{
            header("Location: ../?m=3");
        }
    }
    else
    {
        $select = $connection->find(
            "SELECT id FROM person where cpf=:cpf",
            array(
                ":cpf"=>$cpf
        ));
        if(count($select))
        {
            header("Location: ../?m=2");
        }
        else{
            $name_person = $_POST["name_person"];
            $birth = $_POST['birth'];
            $registration_date = date("Y-m-d h:i:s");

            if(!empty($name_person) and !empty($birth) and !empty($cpf) and !empty($password_acess))
            {

                $insert = $connection->insert(
                    "INSERT INTO person (name_person, birth, cpf, password_acess, registration_date)
                    VALUES ('$name_person', '$birth', '$cpf', '$password_acess', '$registration_date')",
                    null
                );

                $transf_json = json_decode($insert, true);
                if($transf_json["message"])
                {
                    // buscando o id do ususario
                    $id_user = $connection->find(
                        "SELECT id, name_person FROM person where cpf=:cpf",
                        array(
                            ":cpf"=>$cpf
                    ));

                    session_cache_expire(120);
                    $_SESSION["id_user"] = $id_user[0]['id'];
                    $_SESSION["name_person"] = $select[0]["name_person"];
                    header("Location: pages/home");
                }
                else
                {
                    header("Location: ../?m=9");
                }
            }
            else
            {
                header("Location: ../?m=1");
            }
        }
    }
}
else
{
    header("Location: ../?m=1");
}
?>