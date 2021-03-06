<?php
/**
 * @var \Pimcore\Templating\PhpEngine $this
 * @var \Pimcore\Templating\PhpEngine $view
 * @var \Pimcore\Templating\GlobalVariables $app
 */

$this->extend('layout.html.php');

?>
<head>
<style>
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}

input[type=text], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  margin-top: 6px;
  margin-bottom: 16px;
  resize: vertical;
}

input[type=submit] {
  background-color: #4CAF50;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

.container {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}
</style>
</head>

<h3>Feedback Form</h3>

<div class="container">
  <form method = "post">
    <label for="fname"><b>First Name</b></label>
    <input type="text" id="fname" name="firstname" placeholder="Your name.." required>

    <label for="lname"><b>Last Name</b></label>
    <input type="text" id="lname" name="lastname" placeholder="Your last name.." required>

    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Email" name="email" required>

    <label for="subject"><b>Comment</b></label>
    <textarea id="subject" name="comment" placeholder="Write something.." style="height:200px" required></textarea>

    <input type="submit" value="Submit">
  </form>
</div>


    
        <?php
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $comment = $_POST['comment'];
            if(isset($firstname) && isset($lastname) && isset($email) && isset($comment)) {
                $feedback = new \Pimcore\Model\DataObject\Feedback();
        
                $feedback->setKey($email); 
                $feedback->setPublished(true); 
                $feedback->setParentId(40); 
                $feedback->setFirstname($firstname); 
                $feedback->setLastname($lastname); 
                $feedback->setEmail($email); 
                $feedback->setComment($comment); 
                $feedback->save();

                $mail = new \Pimcore\Mail();
                $mail->addTo('shreshtha185singh@gmail.com');
                $mail->setSubject('Products Feedback');
                $mail->setBodyText("Hi,

                One new feedback from ". $_POST['firstname'] ." has been recorded.
                
                Thanks,
                
                Team Admin");
                $mail->send();
            }
            // echo $comment;
            // echo $email;
            // die();  
        ?>  

    <h1><?= $this->input("headline", ["width" => 540]); ?></h1>

    <?php while ($this->block("contentblock")->loop()) { ?>
        <h2><?= $this->input("subline"); ?></h2>
        <?= $this->wysiwyg("content"); ?>
    <?php } ?>