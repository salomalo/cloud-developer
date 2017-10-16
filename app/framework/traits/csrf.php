<?php
namespace Framework\Traits;

trait CSRF
{
    /**
     * Generate token
     */
    private function generateToken()
    {
        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes(32));
        } elseif (function_exists('mcrypt_create_iv')) {
            return bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes(32));
        } else {
            return hash('sha256', uniqid(true).microtime(true));
        }
    }
     
    /**
     * Checks passed token against one in sesssion.
     * 
     * @param string $csrf - Value to compare with current
     * @param bool $use_once - Token is refreshed everytime
     * @param string $target - Token is targeted to a specific form/role..etc
     * @return bool
     */
    public function check_csrf($csrf = null, $use_once = true, $target = null)
    {
        // check both passed and session are not empty
        if (is_null($csrf) || $this->f3->devoid('SESSION.csrf.secret')) {
            return false;
        }

        // check
        if ($target === null) {
            $token = hash_hmac(
                'sha256',
                $this->f3->get('SESSION.csrf.tokens.default.secret'),
                $this->f3->get('SESSION.csrf.tokens.default.secret')
            );
            // expire current
            if ($use_once) {
                $this->f3->set('SESSION.csrf.tokens.default', '');
            }
            // check
            return hash_equals($csrf, $token);
        } else {
            // pre check
            if ($this->f3->devoid('SESSION.csrf.tokens.'.hash('sha256', $target).'.secret')) {
                return false;
            }
            
            $token = hash_hmac(
                'sha256',
                $target,
                $this->f3->get('SESSION.csrf.tokens.'.hash('sha256', $target).'.secret')
            );
            
            // expire current
            if ($use_once) {
                $this->f3->set('SESSION.csrf.tokens.'.hash('sha256', $target), '');
            }
            // check
            return hash_equals($csrf, $token);
        }
    }

    /**
     * Generate and set csrf token/s into session.
     * 
     * Supports single use and by targeting a specific form or action.
     * 
     * @param bool $use_once - Token is refreshed everytime
     * @param string $target - Token is targeted to a specific form/action..etc
     * @return string
     */
    public function set_csrf($use_once = true, $target = null)
    {
        // set secret token
        if ($use_once || $this->f3->devoid('SESSION.csrf.secret')) {
            $this->f3->set('SESSION.csrf.secret', $this->generateToken());
        }

        // sign token
        if ($target === null) {
            $token = hash_hmac(
                'sha256',
                $this->f3->get('SESSION.csrf.secret'),
                $this->f3->get('SESSION.csrf.secret')
            );
            $this->f3->set(
                'SESSION.csrf.tokens.default', [
                    'secret' => $this->f3->get('SESSION.csrf.secret'),
                    'token' => $token
                ]
            );
        } else {
            $token = hash_hmac(
                'sha256',
                $target,
                $this->f3->get('SESSION.csrf.secret')
            );
            $this->f3->set(
                'SESSION.csrf.tokens.'.hash('sha256', $target), [
                    'secret' => $this->f3->get('SESSION.csrf.secret'),
                    'token' => $token,
                ]
            );
        }

        // set single use working token into variables
        header('X-CSRF-TOKEN: '.$token);
        $this->f3->set('csrf', $token);
        
        return $token;
    }
}
