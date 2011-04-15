<?php

/**
 * wpadmin
 *
 * Coded to Zend's coding standards:
 * http://framework.zend.com/manual/en/coding-standard.html
 *
 * File format: UNIX
 * File encoding: UTF8
 * File indentation: Spaces (4). No tabs
 *
 * @copyright   Copyright (c) 2011 88mph. (http://88mph.co)
 * @license     GNU
 * @author      88mph http://88mph.co
 */
 
/**
 * The WpAdmin class provides methods for parsing user input and instantiating
 * the required class.
 *
 * @since   0.0.1
 * @author  Kieran Masterton http://twitter.com/kieranmasterton
 */
class WpAdmin
{
    /**
     * Class name to instantiate.
     *
     * @static
     * @access private
     * @param  string
     */
    private static $_className;
    
    /**
     * Method name to instantiate.
     *
     * @static
     * @access private
     * @param  string
     */
    private static $_methodName;
    
    /**
     * Params to pass to class method.
     *
     * @static
     * @access private
     * @param  array
     */
    private static $_params = array();
    
    /**
     * Function invoked from prompt.
     *
     * @static
     * @access  public
     * @param   $args array
     * @return  void
     */
    public static function exec($args)
    {
        // Parse user input create set class properties.
        self::_parseOptions($args);

        // Check class exists and instantiate object & call method.
        $class      = self::$_className;
        $method     = self::$_methodName;
        
        // Decide how to call the class / method.
        switch($method){
            case 'add':
                $object = $class::add(self::$_params);
            break;
            case 'list':
                $object = $class::listAll(self::$_params);
            break;
            default:
                // Does the class and method requested exist?
                if(method_exists($class, $method)){
                    $object = $class::load(self::$_params['primary']);
                    $object->$method(self::$_params);
                }else{
                    // Else class / method not found, display help.
                    self::displayHelp();
                }
            break;
        }
    }
    
    /**
     * Parse the arguments send from the command line.
     *
     * @static
     * @access  private
     * @param   $args array
     * @return  void
     */
    private static function _parseOptions($args)
    {
        // Set class name and method name based on first & second user input.
        self::$_className = 'WpAdmin_' . ucfirst($args[1]);
        self::$_methodName = strtolower($args[2]);
        
        // Unset no longer required user input.
        unset($args[0], $args[1], $args[2]);
        
        // Special conditional checks for user commands.
        if('WpAdmin_User' == self::$_className && 'add' == self::$_methodName  
                || 'WpAdmin_User' == self::$_className && 'update' == self::$_methodName 
                || 'WpAdmin_User' == self::$_className && 'delete' == self::$_methodName){
            if(preg_match('/^--.*$/', $args[3])){
                echo "Usage: wpadmin user add steve --password=password --email=steve@example.com\n";
                die("[!] You must specify a valid username\n");
            }else{
                self::$_params['username'] = $args[3];
                unset($args[3]);
            }

        }
        
        // Loop through user input create array of key value pairs.
        foreach($args as $k => $value){
            $pair = preg_split('/=/',$value);
            $pair[0] = substr($pair[0], 2);
            if("username" == $pair[0] && 'update' == self::$_methodName){
                self::$_params['newusername'] = $pair[1];
            }else{
                self::$_params[$pair[0]] = $pair[1];
            }
        }
        
        // Special circumstances for primary key for load lookup.
        switch (self::$_className) {
            case 'WpAdmin_User':
                self::$_params['primary'] = self::$_params['username'];
                break;
            default:
                $firstKey = array_keys(self::$_params);
                self::$_params['primary'] = self::$_params[$firstKey[0]];
                break;
        }
        
        // Debug.
        //var_dump(self::$_params); exit;
    }
    
    /**
     * Determines whether the user has said "yes" or "no" to a question prompt on
     * the command line.
     *
     * @static
     * @access  public
     * @param   $args array
     * @return  void
     */
    public static function _parseYesNo($response)
    {
        if('yes' == strtolower($response) || 'y' == strtolower($response)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    /**
     * Displays end-user help.
     *
     * @static
     * @access  public
     * @return  string
     */
    public static function displayHelp()
    {

$str = <<<EOF
Usage: wpadmin [options] [params]

Options:
    User functions:
    
    user add {username} --email={email} --password={password}
    user delete {username}
    user update {username} --role={subscriber, editor, author, administrator}
    user update {username} --password
                           --email
                           --display_name
                           --nickname
                           --first_name
                           --last_name
                           --description
    
    Option functions:
    
    wpadmin option add --{key}={value}
    wpadmin option update --{key}={value}
    wpadmin option delete --{key}
    
    Key/value pairs can be found in the wp_options table. For example this command 
    will disable user comments:
    
    wpadmin option update --default_comment_status=closed


EOF;
            
        echo $str;
    }
    
}