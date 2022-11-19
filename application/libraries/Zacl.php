<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Zacl
{
    // Set the instance variable
    var $CI;

    function __construct()
    {
        // Get the instance
        $this->CI =& get_instance();

        // Set the include path and require the needed files
        set_include_path(get_include_path().PATH_SEPARATOR."application/libraries");
require_once(APPPATH.'libraries/Zend/Acl.php');
require_once(APPPATH.'libraries/Zend/Acl/Role.php');
require_once(APPPATH.'libraries/Zend/Acl/Resource.php');

        $this->acl = new Zend_Acl();

        // Set the default ACL
        $this->acl->addRole(new Zend_Acl_Role('default'));
        $query = $this->CI->db->get('ali_aclresources');
        foreach($query->result() AS $row){
            $this->acl->addResource(new Zend_Acl_Resource($row->resource));
            if($row->default_value == 'true'){
                $this->acl->allow('default', $row->resource);
            }
        }
        // Get the ACL for the roles
        $this->CI->db->order_by("roleorder", "ASC");
        $query = $this->CI->db->get('ali_aclroles');
        foreach($query->result() AS $row){
            $role = (string)$row->name;
            $this->acl->addRole(new Zend_Acl_Role($role), 'default');
            $this->CI->db->from('ali_acl');
            $this->CI->db->join('ali_aclresources', 'ali_acl.resource_id = ali_aclresources.id');
            $this->CI->db->where('type', 'role');
            $this->CI->db->where('type_id', $row->id);
            $subquery = $this->CI->db->get();
            foreach($subquery->result() AS $subrow){
                if($subrow->action == "allow"){
                    $this->acl->allow($role, $subrow->resource);
                } else {
                    $this->acl->deny($role, $subrow->resource);
                }
            }

            // Get the ACL for the users
            $this->CI->db->from('ali_user');
            $this->CI->db->where('roleid', $row->id);
            $userquery = $this->CI->db->get();
            foreach($userquery->result() AS $userrow){
                $this->acl->addRole(new Zend_Acl_Role($userrow->user_ID), $role);
                $this->CI->db->from('ali_acl');
                $this->CI->db->join('ali_aclresources', 'ali_acl.resource_id = ali_aclresources.id');
                $this->CI->db->where('type', 'user');
                $this->CI->db->where('type_id', $userrow->no);
                $usersubquery = $this->CI->db->get();
                foreach($usersubquery->result() AS $usersubrow){
                    if($usersubrow->action == "allow"){
                        $this->acl->allow($userrow->user_ID, $usersubrow->resource);
                    } else {
                        $this->acl->deny($userrow->user_ID, $usersubrow->resource);
                    }
                }
            }
        }
    }

    // Function to check if the current or a preset role has access to a resource
    function check_acl($resource, $role = '')
    {
        if (!$this->acl->has($resource))
        {
            return false;
        }

        if (trim($role)=="") {
            if (isset($this->CI->session->userdata['ALISESS_USERID'])) {
                $role = $this->CI->session->userdata['ALISESS_USERID'];
            }
        }

        if (trim($role)=="") {
            return false;
        }
        return $this->acl->isAllowed($role, $resource);
    }
}