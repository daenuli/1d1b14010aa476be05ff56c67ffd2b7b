<?php
use Symfony\Component\Mailer\Mailer; 
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport; 
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Predis\Client;

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config/app.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $redis = new Client([
        'scheme' => 'tcp',
        'host' => $_ENV['REDIS_HOST'],
        'port' => 6379,
    ]);
    
    $transport = (new EsmtpTransport($_ENV['MAIL_HOST'], $_ENV['MAIL_PORT'], false))
                    ->setUsername($_ENV['MAIL_USERNAME'])
                    ->setPassword($_ENV['MAIL_PASSWORD']);
                    
    $data = $redis->lpop('jobs');
    if ($data !== null) {

        $data  = json_decode($data, true);

        $mailer = new Mailer($transport); 

        $email = (new Email())
                    ->from($_ENV['MAIL_FROM_ADDRESS'])
                    ->to($data['email'])
                    ->subject($data['title'])
                    ->html($data['text']);

        $mailer->send($email);

        $dsn = 'pgsql:dbname='.$_ENV['DB_DATABASE'].';host='.$_ENV['DB_HOST'].';port=5432';

        $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $sql = 'insert into email_sent (email,title,text) values (:email,:title,:text)';

        $stmt = $pdo->prepare($sql);

        $stmt->execute($data);

        echo $data['title'] . " " . $data['text'] . " sent to ".$data['email'];

    }
    
} catch (TransportExceptionInterface $th) {
    echo json_encode(array('success' => false, 'message' => json_encode($e->getMessage())));
}