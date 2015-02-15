!function(t){"function"==typeof define&&define.amd?define(["jquery"],t):t(jQuery)}(function($){$.extend($.fn,{validate:function(t){if(!this.length)return void(t&&t.debug&&window.console&&console.warn("Nothing selected, can't validate, returning nothing."));var e=$.data(this[0],"validator");return e?e:(this.attr("novalidate","novalidate"),e=new $.validator(t,this[0]),$.data(this[0],"validator",e),e.settings.onsubmit&&(this.validateDelegate(":submit","click",function(t){e.settings.submitHandler&&(e.submitButton=t.target),$(t.target).hasClass("cancel")&&(e.cancelSubmit=!0),void 0!==$(t.target).attr("formnovalidate")&&(e.cancelSubmit=!0)}),this.submit(function(t){function i(){var i;return e.settings.submitHandler?(e.submitButton&&(i=$("<input type='hidden'/>").attr("name",e.submitButton.name).val($(e.submitButton).val()).appendTo(e.currentForm)),e.settings.submitHandler.call(e,e.currentForm,t),e.submitButton&&i.remove(),!1):!0}return e.settings.debug&&t.preventDefault(),e.cancelSubmit?(e.cancelSubmit=!1,i()):e.form()?e.pendingRequest?(e.formSubmitted=!0,!1):i():(e.focusInvalid(),!1)})),e)},valid:function(){var t,e;return $(this[0]).is("form")?t=this.validate().form():(t=!0,e=$(this[0].form).validate(),this.each(function(){t=e.element(this)&&t})),t},removeAttrs:function(t){var e={},i=this;return $.each(t.split(/\s/),function(t,s){e[s]=i.attr(s),i.removeAttr(s)}),e},rules:function(t,e){var i=this[0],s,r,n,a,o,u;if(t)switch(s=$.data(i.form,"validator").settings,r=s.rules,n=$.validator.staticRules(i),t){case"add":$.extend(n,$.validator.normalizeRule(e)),delete n.messages,r[i.name]=n,e.messages&&(s.messages[i.name]=$.extend(s.messages[i.name],e.messages));break;case"remove":return e?(u={},$.each(e.split(/\s/),function(t,e){u[e]=n[e],delete n[e],"required"===e&&$(i).removeAttr("aria-required")}),u):(delete r[i.name],n)}return a=$.validator.normalizeRules($.extend({},$.validator.classRules(i),$.validator.attributeRules(i),$.validator.dataRules(i),$.validator.staticRules(i)),i),a.required&&(o=a.required,delete a.required,a=$.extend({required:o},a),$(i).attr("aria-required","true")),a.remote&&(o=a.remote,delete a.remote,a=$.extend(a,{remote:o})),a}}),$.extend($.expr[":"],{blank:function(t){return!$.trim(""+$(t).val())},filled:function(t){return!!$.trim(""+$(t).val())},unchecked:function(t){return!$(t).prop("checked")}}),$.validator=function(t,e){this.settings=$.extend(!0,{},$.validator.defaults,t),this.currentForm=e,this.init()},$.validator.format=function(t,e){return 1===arguments.length?function(){var e=$.makeArray(arguments);return e.unshift(t),$.validator.format.apply(this,e)}:(arguments.length>2&&e.constructor!==Array&&(e=$.makeArray(arguments).slice(1)),e.constructor!==Array&&(e=[e]),$.each(e,function(e,i){t=t.replace(new RegExp("\\{"+e+"\\}","g"),function(){return i})}),t)},$.extend($.validator,{defaults:{messages:{},groups:{},rules:{},errorClass:"error",validClass:"valid",errorElement:"label",focusInvalid:!0,errorContainer:$([]),errorLabelContainer:$([]),onsubmit:!0,ignore:":hidden",ignoreTitle:!1,onfocusin:function(t){this.lastActive=t,this.settings.focusCleanup&&!this.blockFocusCleanup&&(this.settings.unhighlight&&this.settings.unhighlight.call(this,t,this.settings.errorClass,this.settings.validClass),this.hideThese(this.errorsFor(t)))},onfocusout:function(t){this.checkable(t)||!(t.name in this.submitted)&&this.optional(t)||this.element(t)},onkeyup:function(t,e){(9!==e.which||""!==this.elementValue(t))&&(t.name in this.submitted||t===this.lastElement)&&this.element(t)},onclick:function(t){t.name in this.submitted?this.element(t):t.parentNode.name in this.submitted&&this.element(t.parentNode)},highlight:function(t,e,i){"radio"===t.type?this.findByName(t.name).addClass(e).removeClass(i):$(t).addClass(e).removeClass(i)},unhighlight:function(t,e,i){"radio"===t.type?this.findByName(t.name).removeClass(e).addClass(i):$(t).removeClass(e).addClass(i)}},setDefaults:function(t){$.extend($.validator.defaults,t)},messages:{required:"This field is required.",remote:"Please fix this field.",email:"Please enter a valid email address.",url:"Please enter a valid URL.",date:"Please enter a valid date.",dateISO:"Please enter a valid date ( ISO ).",number:"Please enter a valid number.",digits:"Please enter only digits.",creditcard:"Please enter a valid credit card number.",equalTo:"Please enter the same value again.",maxlength:$.validator.format("Please enter no more than {0} characters."),minlength:$.validator.format("Please enter at least {0} characters."),rangelength:$.validator.format("Please enter a value between {0} and {1} characters long."),range:$.validator.format("Please enter a value between {0} and {1}."),max:$.validator.format("Please enter a value less than or equal to {0}."),min:$.validator.format("Please enter a value greater than or equal to {0}.")},autoCreateRanges:!1,prototype:{init:function(){function t(t){var e=$.data(this[0].form,"validator"),i="on"+t.type.replace(/^validate/,""),s=e.settings;s[i]&&!this.is(s.ignore)&&s[i].call(e,this[0],t)}this.labelContainer=$(this.settings.errorLabelContainer),this.errorContext=this.labelContainer.length&&this.labelContainer||$(this.currentForm),this.containers=$(this.settings.errorContainer).add(this.settings.errorLabelContainer),this.submitted={},this.valueCache={},this.pendingRequest=0,this.pending={},this.invalid={},this.reset();var e=this.groups={},i;$.each(this.settings.groups,function(t,i){"string"==typeof i&&(i=i.split(/\s/)),$.each(i,function(i,s){e[s]=t})}),i=this.settings.rules,$.each(i,function(t,e){i[t]=$.validator.normalizeRule(e)}),$(this.currentForm).validateDelegate(":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'] ,[type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], [type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'], [type='radio'], [type='checkbox']","focusin focusout keyup",t).validateDelegate("select, option, [type='radio'], [type='checkbox']","click",t),this.settings.invalidHandler&&$(this.currentForm).bind("invalid-form.validate",this.settings.invalidHandler),$(this.currentForm).find("[required], [data-rule-required], .required").attr("aria-required","true")},form:function(){return this.checkForm(),$.extend(this.submitted,this.errorMap),this.invalid=$.extend({},this.errorMap),this.valid()||$(this.currentForm).triggerHandler("invalid-form",[this]),this.showErrors(),this.valid()},checkForm:function(){this.prepareForm();for(var t=0,e=this.currentElements=this.elements();e[t];t++)this.check(e[t]);return this.valid()},element:function(t){var e=this.clean(t),i=this.validationTargetFor(e),s=!0;return this.lastElement=i,void 0===i?delete this.invalid[e.name]:(this.prepareElement(i),this.currentElements=$(i),s=this.check(i)!==!1,s?delete this.invalid[i.name]:this.invalid[i.name]=!0),$(t).attr("aria-invalid",!s),this.numberOfInvalids()||(this.toHide=this.toHide.add(this.containers)),this.showErrors(),s},showErrors:function(t){if(t){$.extend(this.errorMap,t),this.errorList=[];for(var e in t)this.errorList.push({message:t[e],element:this.findByName(e)[0]});this.successList=$.grep(this.successList,function(e){return!(e.name in t)})}this.settings.showErrors?this.settings.showErrors.call(this,this.errorMap,this.errorList):this.defaultShowErrors()},resetForm:function(){$.fn.resetForm&&$(this.currentForm).resetForm(),this.submitted={},this.lastElement=null,this.prepareForm(),this.hideErrors(),this.elements().removeClass(this.settings.errorClass).removeData("previousValue").removeAttr("aria-invalid")},numberOfInvalids:function(){return this.objectLength(this.invalid)},objectLength:function(t){var e=0,i;for(i in t)e++;return e},hideErrors:function(){this.hideThese(this.toHide)},hideThese:function(t){t.not(this.containers).text(""),this.addWrapper(t).hide()},valid:function(){return 0===this.size()},size:function(){return this.errorList.length},focusInvalid:function(){if(this.settings.focusInvalid)try{$(this.findLastActive()||this.errorList.length&&this.errorList[0].element||[]).filter(":visible").focus().trigger("focusin")}catch(t){}},findLastActive:function(){var t=this.lastActive;return t&&1===$.grep(this.errorList,function(e){return e.element.name===t.name}).length&&t},elements:function(){var t=this,e={};return $(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, [disabled]").not(this.settings.ignore).filter(function(){return!this.name&&t.settings.debug&&window.console&&console.error("%o has no name assigned",this),this.name in e||!t.objectLength($(this).rules())?!1:(e[this.name]=!0,!0)})},clean:function(t){return $(t)[0]},errors:function(){var t=this.settings.errorClass.split(" ").join(".");return $(this.settings.errorElement+"."+t,this.errorContext)},reset:function(){this.successList=[],this.errorList=[],this.errorMap={},this.toShow=$([]),this.toHide=$([]),this.currentElements=$([])},prepareForm:function(){this.reset(),this.toHide=this.errors().add(this.containers)},prepareElement:function(t){this.reset(),this.toHide=this.errorsFor(t)},elementValue:function(t){var e,i=$(t),s=t.type;return"radio"===s||"checkbox"===s?$("input[name='"+t.name+"']:checked").val():"number"===s&&"undefined"!=typeof t.validity?t.validity.badInput?!1:i.val():(e=i.val(),"string"==typeof e?e.replace(/\r/g,""):e)},check:function(t){t=this.validationTargetFor(this.clean(t));var e=$(t).rules(),i=$.map(e,function(t,e){return e}).length,s=!1,r=this.elementValue(t),n,a,o;for(a in e){o={method:a,parameters:e[a]};try{if(n=$.validator.methods[a].call(this,r,t,o.parameters),"dependency-mismatch"===n&&1===i){s=!0;continue}if(s=!1,"pending"===n)return void(this.toHide=this.toHide.not(this.errorsFor(t)));if(!n)return this.formatAndAdd(t,o),!1}catch(u){throw this.settings.debug&&window.console&&console.log("Exception occurred when checking element "+t.id+", check the '"+o.method+"' method.",u),u}}if(!s)return this.objectLength(e)&&this.successList.push(t),!0},customDataMessage:function(t,e){return $(t).data("msg"+e.charAt(0).toUpperCase()+e.substring(1).toLowerCase())||$(t).data("msg")},customMessage:function(t,e){var i=this.settings.messages[t];return i&&(i.constructor===String?i:i[e])},findDefined:function(){for(var t=0;t<arguments.length;t++)if(void 0!==arguments[t])return arguments[t];return void 0},defaultMessage:function(t,e){return this.findDefined(this.customMessage(t.name,e),this.customDataMessage(t,e),!this.settings.ignoreTitle&&t.title||void 0,$.validator.messages[e],"<strong>Warning: No message defined for "+t.name+"</strong>")},formatAndAdd:function(t,e){var i=this.defaultMessage(t,e.method),s=/\$?\{(\d+)\}/g;"function"==typeof i?i=i.call(this,e.parameters,t):s.test(i)&&(i=$.validator.format(i.replace(s,"{$1}"),e.parameters)),this.errorList.push({message:i,element:t,method:e.method}),this.errorMap[t.name]=i,this.submitted[t.name]=i},addWrapper:function(t){return this.settings.wrapper&&(t=t.add(t.parent(this.settings.wrapper))),t},defaultShowErrors:function(){var t,e,i;for(t=0;this.errorList[t];t++)i=this.errorList[t],this.settings.highlight&&this.settings.highlight.call(this,i.element,this.settings.errorClass,this.settings.validClass),this.showLabel(i.element,i.message);if(this.errorList.length&&(this.toShow=this.toShow.add(this.containers)),this.settings.success)for(t=0;this.successList[t];t++)this.showLabel(this.successList[t]);if(this.settings.unhighlight)for(t=0,e=this.validElements();e[t];t++)this.settings.unhighlight.call(this,e[t],this.settings.errorClass,this.settings.validClass);this.toHide=this.toHide.not(this.toShow),this.hideErrors(),this.addWrapper(this.toShow).show()},validElements:function(){return this.currentElements.not(this.invalidElements())},invalidElements:function(){return $(this.errorList).map(function(){return this.element})},showLabel:function(t,e){var i,s,r,n=this.errorsFor(t),a=this.idOrName(t),o=$(t).attr("aria-describedby");n.length?(n.removeClass(this.settings.validClass).addClass(this.settings.errorClass),n.html(e)):(n=$("<"+this.settings.errorElement+">").attr("id",a+"-error").addClass(this.settings.errorClass).html(e||""),i=n,this.settings.wrapper&&(i=n.hide().show().wrap("<"+this.settings.wrapper+"/>").parent()),this.labelContainer.length?this.labelContainer.append(i):this.settings.errorPlacement?this.settings.errorPlacement(i,$(t)):i.insertAfter(t),n.is("label")?n.attr("for",a):0===n.parents("label[for='"+a+"']").length&&(r=n.attr("id"),o?o.match(new RegExp("\b"+r+"\b"))||(o+=" "+r):o=r,$(t).attr("aria-describedby",o),s=this.groups[t.name],s&&$.each(this.groups,function(t,e){e===s&&$("[name='"+t+"']",this.currentForm).attr("aria-describedby",n.attr("id"))}))),!e&&this.settings.success&&(n.text(""),"string"==typeof this.settings.success?n.addClass(this.settings.success):this.settings.success(n,t)),this.toShow=this.toShow.add(n)},errorsFor:function(t){var e=this.idOrName(t),i=$(t).attr("aria-describedby"),s="label[for='"+e+"'], label[for='"+e+"'] *";return i&&(s=s+", #"+i.replace(/\s+/g,", #")),this.errors().filter(s)},idOrName:function(t){return this.groups[t.name]||(this.checkable(t)?t.name:t.id||t.name)},validationTargetFor:function(t){return this.checkable(t)&&(t=this.findByName(t.name).not(this.settings.ignore)[0]),t},checkable:function(t){return/radio|checkbox/i.test(t.type)},findByName:function(t){return $(this.currentForm).find("[name='"+t+"']")},getLength:function(t,e){switch(e.nodeName.toLowerCase()){case"select":return $("option:selected",e).length;case"input":if(this.checkable(e))return this.findByName(e.name).filter(":checked").length}return t.length},depend:function(t,e){return this.dependTypes[typeof t]?this.dependTypes[typeof t](t,e):!0},dependTypes:{"boolean":function(t){return t},string:function(t,e){return!!$(t,e.form).length},"function":function(t,e){return t(e)}},optional:function(t){var e=this.elementValue(t);return!$.validator.methods.required.call(this,e,t)&&"dependency-mismatch"},startRequest:function(t){this.pending[t.name]||(this.pendingRequest++,this.pending[t.name]=!0)},stopRequest:function(t,e){this.pendingRequest--,this.pendingRequest<0&&(this.pendingRequest=0),delete this.pending[t.name],e&&0===this.pendingRequest&&this.formSubmitted&&this.form()?($(this.currentForm).submit(),this.formSubmitted=!1):!e&&0===this.pendingRequest&&this.formSubmitted&&($(this.currentForm).triggerHandler("invalid-form",[this]),this.formSubmitted=!1)},previousValue:function(t){return $.data(t,"previousValue")||$.data(t,"previousValue",{old:null,valid:!0,message:this.defaultMessage(t,"remote")})}},classRuleSettings:{required:{required:!0},email:{email:!0},url:{url:!0},date:{date:!0},dateISO:{dateISO:!0},number:{number:!0},digits:{digits:!0},creditcard:{creditcard:!0}},addClassRules:function(t,e){t.constructor===String?this.classRuleSettings[t]=e:$.extend(this.classRuleSettings,t)},classRules:function(t){var e={},i=$(t).attr("class");return i&&$.each(i.split(" "),function(){this in $.validator.classRuleSettings&&$.extend(e,$.validator.classRuleSettings[this])}),e},attributeRules:function(t){var e={},i=$(t),s=t.getAttribute("type"),r,n;for(r in $.validator.methods)"required"===r?(n=t.getAttribute(r),""===n&&(n=!0),n=!!n):n=i.attr(r),/min|max/.test(r)&&(null===s||/number|range|text/.test(s))&&(n=Number(n)),n||0===n?e[r]=n:s===r&&"range"!==s&&(e[r]=!0);return e.maxlength&&/-1|2147483647|524288/.test(e.maxlength)&&delete e.maxlength,e},dataRules:function(t){var e,i,s={},r=$(t);for(e in $.validator.methods)i=r.data("rule"+e.charAt(0).toUpperCase()+e.substring(1).toLowerCase()),void 0!==i&&(s[e]=i);return s},staticRules:function(t){var e={},i=$.data(t.form,"validator");return i.settings.rules&&(e=$.validator.normalizeRule(i.settings.rules[t.name])||{}),e},normalizeRules:function(t,e){return $.each(t,function(i,s){if(s===!1)return void delete t[i];if(s.param||s.depends){var r=!0;switch(typeof s.depends){case"string":r=!!$(s.depends,e.form).length;break;case"function":r=s.depends.call(e,e)}r?t[i]=void 0!==s.param?s.param:!0:delete t[i]}}),$.each(t,function(i,s){t[i]=$.isFunction(s)?s(e):s}),$.each(["minlength","maxlength"],function(){t[this]&&(t[this]=Number(t[this]))}),$.each(["rangelength","range"],function(){var e;t[this]&&($.isArray(t[this])?t[this]=[Number(t[this][0]),Number(t[this][1])]:"string"==typeof t[this]&&(e=t[this].replace(/[\[\]]/g,"").split(/[\s,]+/),t[this]=[Number(e[0]),Number(e[1])]))}),$.validator.autoCreateRanges&&(t.min&&t.max&&(t.range=[t.min,t.max],delete t.min,delete t.max),t.minlength&&t.maxlength&&(t.rangelength=[t.minlength,t.maxlength],delete t.minlength,delete t.maxlength)),t},normalizeRule:function(t){if("string"==typeof t){var e={};$.each(t.split(/\s/),function(){e[this]=!0}),t=e}return t},addMethod:function(t,e,i){$.validator.methods[t]=e,$.validator.messages[t]=void 0!==i?i:$.validator.messages[t],e.length<3&&$.validator.addClassRules(t,$.validator.normalizeRule(t))},methods:{required:function(t,e,i){if(!this.depend(i,e))return"dependency-mismatch";if("select"===e.nodeName.toLowerCase()){var s=$(e).val();return s&&s.length>0}return this.checkable(e)?this.getLength(t,e)>0:$.trim(t).length>0},email:function(t,e){return this.optional(e)||/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(t)},url:function(t,e){return this.optional(e)||/^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(t)},date:function(t,e){return this.optional(e)||!/Invalid|NaN/.test(new Date(t).toString())},dateISO:function(t,e){return this.optional(e)||/^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/.test(t)},number:function(t,e){return this.optional(e)||/^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(t)},digits:function(t,e){return this.optional(e)||/^\d+$/.test(t)},creditcard:function(t,e){if(this.optional(e))return"dependency-mismatch";if(/[^0-9 \-]+/.test(t))return!1;var i=0,s=0,r=!1,n,a;if(t=t.replace(/\D/g,""),t.length<13||t.length>19)return!1;for(n=t.length-1;n>=0;n--)a=t.charAt(n),s=parseInt(a,10),r&&(s*=2)>9&&(s-=9),i+=s,r=!r;return i%10===0},minlength:function(t,e,i){var s=$.isArray(t)?t.length:this.getLength($.trim(t),e);return this.optional(e)||s>=i},maxlength:function(t,e,i){var s=$.isArray(t)?t.length:this.getLength($.trim(t),e);return this.optional(e)||i>=s},rangelength:function(t,e,i){var s=$.isArray(t)?t.length:this.getLength($.trim(t),e);return this.optional(e)||s>=i[0]&&s<=i[1]},min:function(t,e,i){return this.optional(e)||t>=i},max:function(t,e,i){return this.optional(e)||i>=t},range:function(t,e,i){return this.optional(e)||t>=i[0]&&t<=i[1]},equalTo:function(t,e,i){var s=$(i);return this.settings.onfocusout&&s.unbind(".validate-equalTo").bind("blur.validate-equalTo",function(){$(e).valid()}),t===s.val()},remote:function(t,e,i){if(this.optional(e))return"dependency-mismatch";var s=this.previousValue(e),r,n;return this.settings.messages[e.name]||(this.settings.messages[e.name]={}),s.originalMessage=this.settings.messages[e.name].remote,this.settings.messages[e.name].remote=s.message,i="string"==typeof i&&{url:i}||i,s.old===t?s.valid:(s.old=t,r=this,this.startRequest(e),n={},n[e.name]=t,$.ajax($.extend(!0,{url:i,mode:"abort",port:"validate"+e.name,dataType:"json",data:n,context:r.currentForm,success:function(i){var n=i===!0||"true"===i,a,o,u;r.settings.messages[e.name].remote=s.originalMessage,n?(u=r.formSubmitted,r.prepareElement(e),r.formSubmitted=u,r.successList.push(e),delete r.invalid[e.name],r.showErrors()):(a={},o=i||r.defaultMessage(e,"remote"),a[e.name]=s.message=$.isFunction(o)?o(t):o,r.invalid[e.name]=!0,r.showErrors(a)),s.valid=n,r.stopRequest(e,n)}},i)),"pending")}}}),$.format=function i(){throw"$.format has been deprecated. Please use $.validator.format instead."};var t={},e;$.ajaxPrefilter?$.ajaxPrefilter(function(e,i,s){var r=e.port;"abort"===e.mode&&(t[r]&&t[r].abort(),t[r]=s)}):(e=$.ajax,$.ajax=function(i){var s=("mode"in i?i:$.ajaxSettings).mode,r=("port"in i?i:$.ajaxSettings).port;return"abort"===s?(t[r]&&t[r].abort(),t[r]=e.apply(this,arguments),t[r]):e.apply(this,arguments)}),$.extend($.fn,{validateDelegate:function(t,e,i){return this.bind(e,function(e){var s=$(e.target);return s.is(t)?i.apply(s,arguments):void 0})}})});