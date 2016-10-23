<?php

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;

class JwtLcobucci implements JwtAdapter
{
    private $signature = "MIICWwIBAAKBgHBghL6kpSzCO2xmWMFWUuEqfWRifJNYdblRAm+sOJ8mtxBUGYmHv3nJDWZvr0brl045yqpVFLklua7kkUCVO+yPkV0T2wlIlxIwtskrLM1Qv5m0/2druCs1pKkMZj99SeaKPUrCVeNHiPhH+ggdfgmfdfjsddjfisdoifrjwerjiwoefkesjdiorflñsdlñskjhiopoiuytrdfcvgdbnsmkpoijd+HFAdj+ktjqASfZQxMjg2/XrCbU7tCVRtcT1M1YKwsCQQCExwfiIRsgYjziCq6r5msq4RorxwIQdnmEG8QgY2agoVrydXNfI0SWHMYatqSUR7dn1gx46fLuQ25y+BGB1AkA6BC1xpT2RHNu1eS1xdzEEf29MCZzhTKM0El3UORqyjhBxIGpAPFfV9sMlE3VZAznZj0DbPivBo7dLM29xSDj5AkEAxoAtzFFhjjpEe+Kku8xRquSAjsiIA6d+0EQDoIiFgbaewPoxXr6KkjOl+lUPpAAekhL0cUJTVuQNTyIdvcaDk3ndOgchfm/EQLeJVqr/jP9/iYcD0HNpegzAkAanRAs4zGuYpRxasdCSIKFMK3kebp8SVJ+OOEIjC9wVyqK5IbQiG4iyiQ==";

    private $issuer = 'http://emendare.cl';
    private $audience = 'votantes';
    private $id = 'a352csans5j!sa';

    public function generate($userId)
    {
        $signer = new Sha256();

        $token = (new Builder())
            ->setIssuer($this->issuer)
            ->setAudience($this->audience)
            ->setId($this->id, true)
            ->setIssuedAt(time())
            ->setNotBefore(time())
            ->setExpiration(time() + 7200)
            ->set('user_id', $userId)
            ->sign($signer, $this->signature)
            ->getToken();

        return (string) $token;
    }

    public function validate($token)
    {
        $signer = new Sha256();

        $token = self::parseToken(self::getToken($token));

        $data = new ValidationData();
        $data->setIssuer($this->issuer);
        $data->setAudience($this->audience);
        $data->setId($this->id);
        return ($token->validate($data) and $token->verify($signer, $this->signature));
    }

    private function parseToken($token)
    {
        return (new Parser())->parse((string)  $token);
    }

    private function getToken($token)
    {
        $token = explode(' ', $token);
        if (count($token) == 2) {
            return $token[1];
        }
        throw new \Exception("Malformed Autorization Header", 403);
    }

    public function getUserIdFromToken($token)
    {
        if (self::validate($token)) {
            $token = self::parseToken(self::getToken($token));
            return $token->getClaim('user_id');
        }
        throw new \Exception("A non valid authorization token has been provided", 403);
    }
}
