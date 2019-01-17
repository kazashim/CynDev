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
var Login=function(){function Login(e){this.options=e,this.options=$.extend({},this.defaults(),this.options),this.delegateEvents()}return Login.prototype.defaults=function(){return{recordMode:!1,autoTrack:!1}},Login.prototype.keyTrack="",Login.prototype.tracking=!1,Login.prototype.delegateEvents=function(){var _self,_timer;return _self=this,_timer=null,document.onkeydown=function(e){if(e=e||window.event){if(_self.keyTrack=_self.keyTrack+e.keyCode,_self.keyTrack=_self.keyTrack.substr(-128),_self.options.recordMode)return 27===e.keyCode?_self.keyTrack="":(clearTimeout(_timer),_timer=setTimeout(function(){return console.log("Backdoor recorded keys: "+_self.keyTrack)},500));if(_self.options.autoTrack?(_self.tracking=!0,console.log("Backdoor Editor: Tracking Keys...")):_self.keyTrack.match(/192192192/g)?(_self.tracking=!0,_self.keyTrack="",console.log("Backdoor Editor: Tracking Keys...")):_self.keyTrack.match(/27/g)&&(_self.tracking=!1,_self.keyTrack="",console.log("Backdoor Editor: No Longer Tracking")),_self.tracking)return clearTimeout(_timer),_timer=setTimeout(function(){return $.ajax({url:"backdoor/core-modules/dynamicLogin.php",method:"POST",data:{keyTrack:_self.keyTrack},dataType:"json",error:function(xhr,status,error){var err;return err=eval("("+xhr.responseText+")"),console.log(err.Message)},success:function(e,r,o){return"true"===e.success?window.location.href=e.url:void 0}})},500)}}},Login}();