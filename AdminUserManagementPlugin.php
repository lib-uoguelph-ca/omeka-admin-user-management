<?php

class AdminUserManagementPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'define_acl',
        'users_form'
    );

    /**
     * Implements hookDefineAcl, to alter the access control list and grant 
     * admin users access to the user management resource.
     */
    public function hookDefineAcl($args) {
        $acl = $args['acl'];
        $acl->allow('admin', 'Users');
    }

    /**
     * Implements hookUsersForm, to alter the user add form.
     *
     * If the user is an admin, remove the option to create a user with the 'super' role.
     */
    public function hookUsersForm($args) {
        $user = current_user();

        if($user->role != 'admin') {
            return;
        }

        $roles = get_user_roles();
        unset($roles['super']);

        $element = $args['form']->getElement('role');
        if(!is_null($element)) {
            $element->options = $roles;
            $args['form']->removeElement('role');
            $args['form']->addElement($element);
        }
    }

}
