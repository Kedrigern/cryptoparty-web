<?php namespace Cryptoparty;

use Nette\Security\Permission;

class Acl extends Permission
{
    public function __construct()
    {
        // roles
        $this->addRole("guest");
        $this->addRole("authenticated", "guest");
        $this->addRole("member", "authenticated");
        $this->addRole("redactor", "member");
        $this->addRole("admin", "redactor");

        // resources
        $resources = array(
			"Error",
            "Front:Homepage", "Front:Tag", "Front:Sign", "Front:Resource", "Front:User", "Front:Event",
            "Admin:Home", "Admin:Article", "Admin:Resource", "Admin:Upload", "Admin:Author", "Admin:Tag");
        foreach( $resources as $res) {
            $this->addResource($res);
        }

        // privileges
        $this->allow(array("guest"), array("Error", "Front:Homepage", "Front:Tag", "Front:Sign", "Front:Resource", "Front:Event"), Permission::ALL);
        $this->allow(array("authenticated"), array("Front:User"));
        $this->allow(array("redactor"), \Nette\Security\Permission::ALL, \Nette\Security\Permission::ALL );
    }
}