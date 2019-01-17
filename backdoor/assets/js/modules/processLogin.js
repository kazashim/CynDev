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
$(document).ready(function(){$alertBox=$("#alert"),$alert=$("<div></div>").addClass("login-box-alert"),$("#loginBtn").click(function(){$.ajax({headers:{"BD-API-SESSION":bdSession},url:baseUrlStr+"/backdoor/core-modules/user-manager/login.php",method:"POST",data:{user:$("#email").val(),pass:$("#pass").val()},dataType:"json",error:function(xhr,status,error){err=eval("("+xhr.responseText+")"),$alert.html(err.Message),$alertBox.html($alert)},success:function(r){return"true"===r.success?window.location.href=r.url:($alert.html("There was an error logging you in."),void $alertBox.html($alert))}})})});