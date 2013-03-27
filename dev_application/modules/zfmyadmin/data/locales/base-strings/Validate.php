<?php
// you can not change indexes in this file - system messages
$translateBase = array(
    Zend_Validate_Alnum::NOT_ALNUM => _("'%value%' contains characters which are non alphabetic and no digits"),
    Zend_Validate_Alnum::STRING_EMPTY => _("'%value%' is an empty string") ,

    Zend_Validate_Alpha::NOT_ALPHA => _("'%value%' contains non alphabetic characters") ,
    Zend_Validate_Alpha::STRING_EMPTY => _("'%value%' is an empty string") ,

    Zend_Validate_Digits::NOT_DIGITS => _("'%value%' must contain only digits") ,
    Zend_Validate_Digits::STRING_EMPTY => _("'%value%' is an empty string") ,

    Zend_Validate_EmailAddress::INVALID            => _("Invalid type given. String expected") ,
    Zend_Validate_EmailAddress::INVALID_FORMAT     => _("'%value%' is not valid email address in the basic format local-part@hostname") ,
    Zend_Validate_EmailAddress::INVALID_HOSTNAME   => _("'%hostname%' is not valid hostname for email address '%value%'") ,
    Zend_Validate_EmailAddress::INVALID_MX_RECORD  => _("'%hostname%' does not appear to have a valid MX record for the email address '%value%'") ,
    Zend_Validate_EmailAddress::INVALID_SEGMENT    => _("'%hostname%' is not in a routable network segment. The email address '%value%' should not be resolved from public network") ,

    Zend_Validate_Identical::NOT_SAME      => _("The two given tokens do not match") ,
    Zend_Validate_Identical::MISSING_TOKEN => _('No token was provided to match against') ,

    Zend_Validate_InArray::NOT_IN_ARRAY => _("'%value%' was not found in the haystack") ,

    Zend_Validate_Int::INVALID => _("Invalid type given. String or integer expected") ,
    Zend_Validate_Int::NOT_INT => _("'%value%' does not appear to be an integer") ,

    Zend_Validate_NotEmpty::IS_EMPTY => _("Value is required and can't be empty") ,
    Zend_Validate_NotEmpty::INVALID  => _("Invalid type given. String, integer, float, boolean or array expected") ,

    Zend_Validate_Regex::NOT_MATCH => _("'%value%' does not match against pattern '%pattern%'") ,

    Zend_Validate_StringLength::TOO_SHORT => _("'%value%' is less than %min% characters long") ,
    Zend_Validate_StringLength::TOO_LONG  => _("'%value%' is more than %max% characters long") ,
);
