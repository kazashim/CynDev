/*
@category		PHP CMS
@package		Backdoor - Your Online Companion Editor
@author			Shannon Reca <iam@shannonreca.com>
@copyright	2015 Shannon Reca
@usage			For more specific usage see the documentation at http://backdoor.shannonreca.com
@license		http://codecanyon.net/licenses/standard
@version		build-020216 v1.3
@since			02/02/16
@feedback		Email: feedback@shannonreca.com
*/
define(["jquery","backdoor","svg4everybody"],function(e,o){return document.Backdoor=new o({webDevDir:baseUrlStr,token:bdSession,saveAlerts:savAlr,chatRefresh:cr,chatLogRefresh:clr,chatOnlineRefresh:cor})});