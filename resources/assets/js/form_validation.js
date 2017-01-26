+function(a){"use strict";function b(b){return b.is('[type="checkbox"]')?b.prop("checked"):b.is('[type="radio"]')?!!a('[name="'+b.attr("name")+'"]:checked').length:b.val()}function c(b){return this.each(function(){var c=a(this),e=a.extend({},d.DEFAULTS,c.data(),"object"==typeof b&&b),f=c.data("bs.validator");(f||"destroy"!=b)&&(f||c.data("bs.validator",f=new d(this,e)),"string"==typeof b&&f[b]())})}var d=function(c,e){this.options=e,this.validators=a.extend({},d.VALIDATORS,e.custom),this.$element=a(c),this.$btn=a('button[type="submit"], input[type="submit"]').filter('[form="'+this.$element.attr("id")+'"]').add(this.$element.find('input[type="submit"], button[type="submit"]')),this.update(),this.$element.on("input.bs.validator change.bs.validator focusout.bs.validator",a.proxy(this.onInput,this)),this.$element.on("submit.bs.validator",a.proxy(this.onSubmit,this)),this.$element.on("reset.bs.validator",a.proxy(this.reset,this)),this.$element.find("[data-match]").each(function(){var c=a(this),d=c.data("match");a(d).on("input.bs.validator",function(a){b(c)&&c.trigger("input.bs.validator")})}),this.$inputs.filter(function(){return b(a(this))}).trigger("focusout"),this.$element.attr("novalidate",!0),this.toggleSubmit()};d.VERSION="0.11.5",d.INPUT_SELECTOR=':input:not([type="hidden"], [type="submit"], [type="reset"], button)',d.FOCUS_OFFSET=20,d.DEFAULTS={delay:500,html:!1,disable:!0,focus:!0,custom:{},errors:{match:"Does not match",minlength:"Not long enough"},feedback:{success:"glyphicon-ok",error:"glyphicon-remove"}},d.VALIDATORS={"native":function(a){var b=a[0];return b.checkValidity?!b.checkValidity()&&!b.validity.valid&&(b.validationMessage||"error!"):void 0},match:function(b){var c=b.data("match");return b.val()!==a(c).val()&&d.DEFAULTS.errors.match},minlength:function(a){var b=a.data("minlength");return a.val().length<b&&d.DEFAULTS.errors.minlength}},d.prototype.update=function(){return this.$inputs=this.$element.find(d.INPUT_SELECTOR).add(this.$element.find('[data-validate="true"]')).not(this.$element.find('[data-validate="false"]')),this},d.prototype.onInput=function(b){var c=this,d=a(b.target),e="focusout"!==b.type;this.$inputs.is(d)&&this.validateInput(d,e).done(function(){c.toggleSubmit()})},d.prototype.validateInput=function(c,d){var e=(b(c),c.data("bs.validator.errors"));c.is('[type="radio"]')&&(c=this.$element.find('input[name="'+c.attr("name")+'"]'));var f=a.Event("validate.bs.validator",{relatedTarget:c[0]});if(this.$element.trigger(f),!f.isDefaultPrevented()){var g=this;return this.runValidators(c).done(function(b){c.data("bs.validator.errors",b),b.length?d?g.defer(c,g.showErrors):g.showErrors(c):g.clearErrors(c),e&&b.toString()===e.toString()||(f=b.length?a.Event("invalid.bs.validator",{relatedTarget:c[0],detail:b}):a.Event("valid.bs.validator",{relatedTarget:c[0],detail:e}),g.$element.trigger(f)),g.toggleSubmit(),g.$element.trigger(a.Event("validated.bs.validator",{relatedTarget:c[0]}))})}},d.prototype.runValidators=function(c){function d(a){return c.data(a+"-error")}function e(){var a=c[0].validity;return a.typeMismatch?c.data("type-error"):a.patternMismatch?c.data("pattern-error"):a.stepMismatch?c.data("step-error"):a.rangeOverflow?c.data("max-error"):a.rangeUnderflow?c.data("min-error"):a.valueMissing?c.data("required-error"):null}function f(){return c.data("error")}function g(a){return d(a)||e()||f()}var h=[],i=a.Deferred();return c.data("bs.validator.deferred")&&c.data("bs.validator.deferred").reject(),c.data("bs.validator.deferred",i),a.each(this.validators,a.proxy(function(a,d){var e=null;(b(c)||c.attr("required"))&&(c.data(a)||"native"==a)&&(e=d.call(this,c))&&(e=g(a)||e,!~h.indexOf(e)&&h.push(e))},this)),!h.length&&b(c)&&c.data("remote")?this.defer(c,function(){var d={};d[c.attr("name")]=b(c),a.get(c.data("remote"),d).fail(function(a,b,c){h.push(g("remote")||c)}).always(function(){i.resolve(h)})}):i.resolve(h),i.promise()},d.prototype.validate=function(){var b=this;return a.when(this.$inputs.map(function(c){return b.validateInput(a(this),!1)})).then(function(){b.toggleSubmit(),b.focusError()}),this},d.prototype.focusError=function(){if(this.options.focus){var b=this.$element.find(".has-error:first :input");0!==b.length&&(a("html, body").animate({scrollTop:b.offset().top-d.FOCUS_OFFSET},250),b.focus())}},d.prototype.showErrors=function(b){var c=this.options.html?"html":"text",d=b.data("bs.validator.errors"),e=b.closest(".form-group"),f=e.find(".help-block.with-errors"),g=e.find(".form-control-feedback");d.length&&(d=a("<ul/>").addClass("list-unstyled").append(a.map(d,function(b){return a("<li/>")[c](b)})),void 0===f.data("bs.validator.originalContent")&&f.data("bs.validator.originalContent",f.html()),f.empty().append(d),e.addClass("has-error has-danger"),e.hasClass("has-feedback")&&g.removeClass(this.options.feedback.success)&&g.addClass(this.options.feedback.error)&&e.removeClass("has-success"))},d.prototype.clearErrors=function(a){var c=a.closest(".form-group"),d=c.find(".help-block.with-errors"),e=c.find(".form-control-feedback");d.html(d.data("bs.validator.originalContent")),c.removeClass("has-error has-danger has-success"),c.hasClass("has-feedback")&&e.removeClass(this.options.feedback.error)&&e.removeClass(this.options.feedback.success)&&b(a)&&e.addClass(this.options.feedback.success)&&c.addClass("has-success")},d.prototype.hasErrors=function(){function b(){return!!(a(this).data("bs.validator.errors")||[]).length}return!!this.$inputs.filter(b).length},d.prototype.isIncomplete=function(){function c(){var c=b(a(this));return!("string"==typeof c?a.trim(c):c)}return!!this.$inputs.filter("[required]").filter(c).length},d.prototype.onSubmit=function(a){this.validate(),(this.isIncomplete()||this.hasErrors())&&a.preventDefault()},d.prototype.toggleSubmit=function(){this.options.disable&&this.$btn.toggleClass("disabled",this.isIncomplete()||this.hasErrors())},d.prototype.defer=function(b,c){return c=a.proxy(c,this,b),this.options.delay?(window.clearTimeout(b.data("bs.validator.timeout")),void b.data("bs.validator.timeout",window.setTimeout(c,this.options.delay))):c()},d.prototype.reset=function(){return this.$element.find(".form-control-feedback").removeClass(this.options.feedback.error).removeClass(this.options.feedback.success),this.$inputs.removeData(["bs.validator.errors","bs.validator.deferred"]).each(function(){var b=a(this),c=b.data("bs.validator.timeout");window.clearTimeout(c)&&b.removeData("bs.validator.timeout")}),this.$element.find(".help-block.with-errors").each(function(){var b=a(this),c=b.data("bs.validator.originalContent");b.removeData("bs.validator.originalContent").html(c)}),this.$btn.removeClass("disabled"),this.$element.find(".has-error, .has-danger, .has-success").removeClass("has-error has-danger has-success"),this},d.prototype.destroy=function(){return this.reset(),this.$element.removeAttr("novalidate").removeData("bs.validator").off(".bs.validator"),this.$inputs.off(".bs.validator"),this.options=null,this.validators=null,this.$element=null,this.$btn=null,this};var e=a.fn.validator;a.fn.validator=c,a.fn.validator.Constructor=d,a.fn.validator.noConflict=function(){return a.fn.validator=e,this},a(window).on("load",function(){a('form[data-toggle="validator"]').each(function(){var b=a(this);c.call(b,b.data())})})}(jQuery);
(function(){var t,e,n,r,a,o,i,l,u,s,c,h,p,g,v,f,d,m,y,C,T,w,$,D,S=[].slice,k=[].indexOf||function(t){for(var e=0,n=this.length;n>e;e++)if(e in this&&this[e]===t)return e;return-1};t=window.jQuery||window.Zepto||window.$,t.payment={},t.payment.fn={},t.fn.payment=function(){var e,n;return n=arguments[0],e=2<=arguments.length?S.call(arguments,1):[],t.payment.fn[n].apply(this,e)},a=/(\d{1,4})/g,t.payment.cards=r=[{type:"elo",patterns:[401178,401179,431274,438935,451416,457393,457631,457632,504175,506699,5067,509,627780,636297,636368,650,6516,6550],format:a,length:[16],cvcLength:[3],luhn:!0},{type:"maestro",patterns:[5018,502,503,506,56,58,639,6220,67],format:a,length:[12,13,14,15,16,17,18,19],cvcLength:[3],luhn:!0},{type:"forbrugsforeningen",patterns:[600],format:a,length:[16],cvcLength:[3],luhn:!0},{type:"dankort",patterns:[5019],format:a,length:[16],cvcLength:[3],luhn:!0},{type:"visa",patterns:[4],format:a,length:[13,16],cvcLength:[3],luhn:!0},{type:"mastercard",patterns:[51,52,53,54,55,22,23,24,25,26,27],format:a,length:[16],cvcLength:[3],luhn:!0},{type:"amex",patterns:[34,37],format:/(\d{1,4})(\d{1,6})?(\d{1,5})?/,length:[15],cvcLength:[3,4],luhn:!0},{type:"dinersclub",patterns:[30,36,38,39],format:/(\d{1,4})(\d{1,6})?(\d{1,4})?/,length:[14],cvcLength:[3],luhn:!0},{type:"discover",patterns:[60,64,65,622],format:a,length:[16],cvcLength:[3],luhn:!0},{type:"unionpay",patterns:[62,88],format:a,length:[16,17,18,19],cvcLength:[3],luhn:!1},{type:"jcb",patterns:[35],format:a,length:[16],cvcLength:[3],luhn:!0}],e=function(t){var e,n,a,o,i,l,u,s;for(t=(t+"").replace(/\D/g,""),o=0,l=r.length;l>o;o++)for(e=r[o],s=e.patterns,i=0,u=s.length;u>i;i++)if(a=s[i],n=a+"",t.substr(0,n.length)===n)return e},n=function(t){var e,n,a;for(n=0,a=r.length;a>n;n++)if(e=r[n],e.type===t)return e},p=function(t){var e,n,r,a,o,i;for(r=!0,a=0,n=(t+"").split("").reverse(),o=0,i=n.length;i>o;o++)e=n[o],e=parseInt(e,10),(r=!r)&&(e*=2),e>9&&(e-=9),a+=e;return a%10===0},h=function(t){var e;return null!=t.prop("selectionStart")&&t.prop("selectionStart")!==t.prop("selectionEnd")?!0:null!=("undefined"!=typeof document&&null!==document&&null!=(e=document.selection)?e.createRange:void 0)&&document.selection.createRange().text?!0:!1},$=function(t,e){var n,r,a,o,i,l;try{r=e.prop("selectionStart")}catch(u){o=u,r=null}return i=e.val(),e.val(t),null!==r&&e.is(":focus")?(r===i.length&&(r=t.length),i!==t&&(l=i.slice(r-1,+r+1||9e9),n=t.slice(r-1,+r+1||9e9),a=t[r],/\d/.test(a)&&l===""+a+" "&&n===" "+a&&(r+=1)),e.prop("selectionStart",r),e.prop("selectionEnd",r)):void 0},m=function(t){var e,n,r,a,o,i,l,u;for(null==t&&(t=""),r="０１２３４５６７８９",a="0123456789",i="",e=t.split(""),l=0,u=e.length;u>l;l++)n=e[l],o=r.indexOf(n),o>-1&&(n=a[o]),i+=n;return i},d=function(e){var n;return n=t(e.currentTarget),setTimeout(function(){var t;return t=n.val(),t=m(t),t=t.replace(/\D/g,""),$(t,n)})},v=function(e){var n;return n=t(e.currentTarget),setTimeout(function(){var e;return e=n.val(),e=m(e),e=t.payment.formatCardNumber(e),$(e,n)})},l=function(n){var r,a,o,i,l,u,s;return o=String.fromCharCode(n.which),!/^\d+$/.test(o)||(r=t(n.currentTarget),s=r.val(),a=e(s+o),i=(s.replace(/\D/g,"")+o).length,u=16,a&&(u=a.length[a.length.length-1]),i>=u||null!=r.prop("selectionStart")&&r.prop("selectionStart")!==s.length)?void 0:(l=a&&"amex"===a.type?/^(\d{4}|\d{4}\s\d{6})$/:/(?:^|\s)(\d{4})$/,l.test(s)?(n.preventDefault(),setTimeout(function(){return r.val(s+" "+o)})):l.test(s+o)?(n.preventDefault(),setTimeout(function(){return r.val(s+o+" ")})):void 0)},o=function(e){var n,r;return n=t(e.currentTarget),r=n.val(),8!==e.which||null!=n.prop("selectionStart")&&n.prop("selectionStart")!==r.length?void 0:/\d\s$/.test(r)?(e.preventDefault(),setTimeout(function(){return n.val(r.replace(/\d\s$/,""))})):/\s\d?$/.test(r)?(e.preventDefault(),setTimeout(function(){return n.val(r.replace(/\d$/,""))})):void 0},f=function(e){var n;return n=t(e.currentTarget),setTimeout(function(){var e;return e=n.val(),e=m(e),e=t.payment.formatExpiry(e),$(e,n)})},u=function(e){var n,r,a;return r=String.fromCharCode(e.which),/^\d+$/.test(r)?(n=t(e.currentTarget),a=n.val()+r,/^\d$/.test(a)&&"0"!==a&&"1"!==a?(e.preventDefault(),setTimeout(function(){return n.val("0"+a+" / ")})):/^\d\d$/.test(a)?(e.preventDefault(),setTimeout(function(){var t,e;return t=parseInt(a[0],10),e=parseInt(a[1],10),e>2&&0!==t?n.val("0"+t+" / "+e):n.val(""+a+" / ")})):void 0):void 0},s=function(e){var n,r,a;return r=String.fromCharCode(e.which),/^\d+$/.test(r)?(n=t(e.currentTarget),a=n.val(),/^\d\d$/.test(a)?n.val(""+a+" / "):void 0):void 0},c=function(e){var n,r,a;return a=String.fromCharCode(e.which),"/"===a||" "===a?(n=t(e.currentTarget),r=n.val(),/^\d$/.test(r)&&"0"!==r?n.val("0"+r+" / "):void 0):void 0},i=function(e){var n,r;return n=t(e.currentTarget),r=n.val(),8!==e.which||null!=n.prop("selectionStart")&&n.prop("selectionStart")!==r.length?void 0:/\d\s\/\s$/.test(r)?(e.preventDefault(),setTimeout(function(){return n.val(r.replace(/\d\s\/\s$/,""))})):void 0},g=function(e){var n;return n=t(e.currentTarget),setTimeout(function(){var t;return t=n.val(),t=m(t),t=t.replace(/\D/g,"").slice(0,4),$(t,n)})},w=function(t){var e;return t.metaKey||t.ctrlKey?!0:32===t.which?!1:0===t.which?!0:t.which<33?!0:(e=String.fromCharCode(t.which),!!/[\d\s]/.test(e))},C=function(n){var r,a,o,i;return r=t(n.currentTarget),o=String.fromCharCode(n.which),/^\d+$/.test(o)&&!h(r)?(i=(r.val()+o).replace(/\D/g,""),a=e(i),a?i.length<=a.length[a.length.length-1]:i.length<=16):void 0},T=function(e){var n,r,a;return n=t(e.currentTarget),r=String.fromCharCode(e.which),/^\d+$/.test(r)&&!h(n)?(a=n.val()+r,a=a.replace(/\D/g,""),a.length>6?!1:void 0):void 0},y=function(e){var n,r,a;return n=t(e.currentTarget),r=String.fromCharCode(e.which),/^\d+$/.test(r)&&!h(n)?(a=n.val()+r,a.length<=4):void 0},D=function(e){var n,a,o,i,l;return n=t(e.currentTarget),l=n.val(),i=t.payment.cardType(l)||"unknown",n.hasClass(i)?void 0:(a=function(){var t,e,n;for(n=[],t=0,e=r.length;e>t;t++)o=r[t],n.push(o.type);return n}(),n.removeClass("unknown"),n.removeClass(a.join(" ")),n.addClass(i),n.toggleClass("identified","unknown"!==i),n.trigger("payment.cardType",i))},t.payment.fn.formatCardCVC=function(){return this.on("keypress",w),this.on("keypress",y),this.on("paste",g),this.on("change",g),this.on("input",g),this},t.payment.fn.formatCardExpiry=function(){return this.on("keypress",w),this.on("keypress",T),this.on("keypress",u),this.on("keypress",c),this.on("keypress",s),this.on("keydown",i),this.on("change",f),this.on("input",f),this},t.payment.fn.formatCardNumber=function(){return this.on("keypress",w),this.on("keypress",C),this.on("keypress",l),this.on("keydown",o),this.on("keyup",D),this.on("paste",v),this.on("change",v),this.on("input",v),this.on("input",D),this},t.payment.fn.restrictNumeric=function(){return this.on("keypress",w),this.on("paste",d),this.on("change",d),this.on("input",d),this},t.payment.fn.cardExpiryVal=function(){return t.payment.cardExpiryVal(t(this).val())},t.payment.cardExpiryVal=function(t){var e,n,r,a;return a=t.split(/[\s\/]+/,2),e=a[0],r=a[1],2===(null!=r?r.length:void 0)&&/^\d+$/.test(r)&&(n=(new Date).getFullYear(),n=n.toString().slice(0,2),r=n+r),e=parseInt(e,10),r=parseInt(r,10),{month:e,year:r}},t.payment.validateCardNumber=function(t){var n,r;return t=(t+"").replace(/\s+|-/g,""),/^\d+$/.test(t)?(n=e(t),n?(r=t.length,k.call(n.length,r)>=0&&(n.luhn===!1||p(t))):!1):!1},t.payment.validateCardExpiry=function(e,n){var r,a,o;return"object"==typeof e&&"month"in e&&(o=e,e=o.month,n=o.year),e&&n?(e=t.trim(e),n=t.trim(n),/^\d+$/.test(e)&&/^\d+$/.test(n)&&e>=1&&12>=e?(2===n.length&&(n=70>n?"20"+n:"19"+n),4!==n.length?!1:(a=new Date(n,e),r=new Date,a.setMonth(a.getMonth()-1),a.setMonth(a.getMonth()+1,1),a>r)):!1):!1},t.payment.validateCardCVC=function(e,r){var a,o;return e=t.trim(e),/^\d+$/.test(e)?(a=n(r),null!=a?(o=e.length,k.call(a.cvcLength,o)>=0):e.length>=3&&e.length<=4):!1},t.payment.cardType=function(t){var n;return t?(null!=(n=e(t))?n.type:void 0)||null:null},t.payment.formatCardNumber=function(n){var r,a,o,i;return n=n.replace(/\D/g,""),(r=e(n))?(o=r.length[r.length.length-1],n=n.slice(0,o),r.format.global?null!=(i=n.match(r.format))?i.join(" "):void 0:(a=r.format.exec(n),null!=a?(a.shift(),a=t.grep(a,function(t){return t}),a.join(" ")):void 0)):n},t.payment.formatExpiry=function(t){var e,n,r,a;return(n=t.match(/^\D*(\d{1,2})(\D+)?(\d{1,4})?/))?(e=n[1]||"",r=n[2]||"",a=n[3]||"",a.length>0?r=" / ":" /"===r?(e=e.substring(0,1),r=""):2===e.length||r.length>0?r=" / ":1===e.length&&"0"!==e&&"1"!==e&&(e="0"+e,r=" / "),e+r+a):""}}).call(this);