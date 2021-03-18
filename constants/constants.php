<?php 
//server errors
define('ERROR_CONNECTING_DATABASE',     101);

//REQUEST ERRORS
define('INVALID_REQUEST_METHOD',    201);

//CONTENT TYPE ERRORS
define('INVALID_CONTENT_TYPE',      301);

//API ERRORS
define('APINAME_REQUIRED',      401);
define('PARAM_REQUIRED',      402);
define('INVALID_API',    403);
define('EMPTY_FIELD', 404);
define('INVALID_DATATYPE',  405);
define('PROVIDE_PARAM',      406);

//general errors
define('RECORD_EXIST',  501);
define('RECORD_DOESNT_EXIST',  502);
define('NO_DATA',   503);

//general success
define('RECORD_ADDED',  500);

//code execution error
define('EXECUTION_ERROR',   601);

//custom full record
define('FULL_RECORD',   701);

//success login
define('SUCCESS_LOGIN', 900);