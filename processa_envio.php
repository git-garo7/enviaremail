<?php

	require "./bibliotecas/PHPMailer/Exception.php";
	require "./bibliotecas/PHPMailer/OAuth.php";
	require "./bibliotecas/PHPMailer/PHPMailer.php";
	require "./bibliotecas/PHPMailer/POP3.php";//recebimento/protocolo
	require "./bibliotecas/PHPMailer/SMTP.php";//envio/protocolo
	//namespaces
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

//print_r($_POST);
 
 class Mensagem{
 	private $para = null;
 	private $assunto = null;
 	private $mensagem = null;
 	public $status = array('codigo_status'=> null,'descricao_status'=> '');


 	public function __get ($atributo) {
 		return $this->$atributo;
 	}
 	public function __set($atributo,$valor){
 		$this->$atributo = $valor;
 	}
 	public function mensagemValida(){
 		if(empty($this->para) || empty($this->assunto) || empty($this->mensagem))//verifica se campos estão vazios 
 		{ return false; 

 		}
 		return true;
 	}
 }

 $mensagem = new Mensagem();

 $mensagem->__set('para',$_POST['para']);//ja está nos nomes lá na index.php
 $mensagem->__set('assunto',$_POST['assunto']);
 $mensagem->__set('mensagem',$_POST['mensagem']);

 //print_r($mensagem);

 if(!$mensagem->mensagemValida()){
 	echo "mansagem valida!!!";
 	//die();evitar de ,atar aplicação usar heador location.
 	header('location:index.php'); 
 }
// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = false;//mudar para false praesconder o log                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'colocar email aqui';                     // SMTP username
    $mail->Password   = 'colocar senha aqui';                               // SMTP password
    $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('joao-git7@gmail.com', 'joao remetente');
    $mail->addAddress($mensagem->__get('para'));     // Add a recipient
    
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    // Attachments
   // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $mensagem->__get('assunto');
    $mail->Body    = $mensagem->__get('mensagem');//suporta tags HTML
    $mail->AltBody = 'necessário utilizar um client com suporte HTML';//não tem suporte html

    $mail->send();
    $mensagem->status['codigo_status'] = 1;
    $mensagem->status['descricao_status'] = 'e-mail enviado com sucesso';
     
} catch (Exception $e) {
	$mensagem->status['codigo_status'] = 2;
    $mensagem->status['descricao_status'] = 'não foi possível enviar o email,tente novamente mais tarde. detalhes do erro:'. $mail->ErrorInfo;
    
}
?>
<html>
<head>
	<meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"><!--inclusão do bootstrap-->

</head>
<body>
	<div class="container">
		<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="emailenviado.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>
			<div class="row">
				<div class="col-md-12"></div>
				<?if($mensagem->status['codigo_status'] == 1) {?>
					<div class="container">
						<h1 class="display-4 text-success">Sucesso</h1>
						<p><?= $mensagem->status['descricao_status'] ?></p>
						<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
					</div>
				<?}?> 
				<?if($mensagem->status['codigo_status'] == 2) {?>
					 <div class="container">
					 	<img class="d-block mx-auto mb-2" src="emailnao.jpeg" alt="" width="72" height="72">
						<h1 class="display-4 text-danger">OPS!,Houve um erro</h1>
						<p><?= $mensagem->status['descricao_status'] ?></p>
						<a href="index.php" class="btn btn-danger btn-lg mt-5 text-white">Voltar</a>
					</div>
				<?}?>
			</div>
	</div>
</body>
</html>
