<?php

namespace App\Service;

class RolesChecker
{
    public const USER = 'utilisateur';
    public const AGENT = 'utilisateur, agent';
    public const MAYOR = 'utilisateur, agent, maire';
    public const ADMIN = 'utilisateur, agent, maire, admin';

    public const DB_USER = 'ROLE_USER';
    public const DB_AGENT = 'ROLE_AGENT';
    public const DB_MAYOR = 'ROLE_MAYOR';
    public const DB_ADMIN = 'ROLE_ADMIN';

    public const TITLE_USER = 'Liste des utilisateurs';
    public const TITLE_AGENT = 'Liste des agents';
    public const TITLE_MAYOR = 'Maire';
    public const TITLE_ADMIN = 'Administrateur';

    public const URL_USERS = 'utilisateurs';
    public const URL_AGENTS = 'agents';
    public const URL_MAYOR = 'maire';
    public const URL_ADMIN = 'administrateur';

    private $roles = null;
    private $role = null;
    private $title = null;
    private $url = null;

    public function __construct($roles) {
        $this->configure($roles);
    }

    /**
     * Cherche les roles et les enregistre dans $userType
     * Retourne la description des types
     *
     * @param array $roles
     * @return void
     */
    public function configure(array $roles)
    {
        switch ($roles) {
            case in_array(self::DB_ADMIN, $roles, true): $this->setAdmin();
            break;

            case in_array(self::DB_MAYOR, $roles, true): $this->setMayor();
            break;

            case in_array(self::DB_AGENT, $roles, true): $this->setAgent();
            break;

            case in_array(self::DB_USER, $roles, true): $this->setUser();
            break;
        }
    }

    public static function urlToTitle($url)
    {
        switch ($url) {
            case self::URL_USERS:
                return self::TITLE_USER;
                break;
            case self::URL_AGENTS:
                return self::TITLE_AGENT;
                break;
            case self::URL_MAYOR:
                return self::TITLE_MAYOR;
                break;
            case self::URL_ADMIN:
                return self::TITLE_ADMIN;
                break;
        }
    }

    public function setUser()
    {
        $this->setRoles(self::USER);
        $this->setRole(self::DB_USER);
        $this->setTitle(self::TITLE_USER);
        $this->setUrl(self::URL_USERS);
    }

    public function setAgent()
    {
        $this->setRoles(self::AGENT);
        $this->setRole(self::DB_AGENT);
        $this->setTitle(self::TITLE_AGENT);
        $this->setUrl(self::URL_AGENTS);
    }

    public function setMayor()
    {
        $this->setRoles(self::MAYOR);
        $this->setRole(self::DB_MAYOR);
        $this->setTitle(self::TITLE_MAYOR);
        $this->setUrl(self::URL_MAYOR);
    }

    public function setAdmin()
    {
        $this->setRoles(self::ADMIN);
        $this->setRole(self::DB_ADMIN);
        $this->setTitle(self::TITLE_ADMIN);
        $this->setUrl(self::URL_ADMIN);
    }

    /**
     * Get the value of roles
     */ 
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set the value of roles
     *
     * @return  self
     */ 
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get the value of role
     */ 
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @return  self
     */ 
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of url
     */ 
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set the value of url
     *
     * @return  self
     */ 
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}
