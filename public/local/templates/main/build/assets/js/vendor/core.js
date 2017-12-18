!function(t){"function"==typeof define&&define.amd?define(["jquery"],t):t(jQuery)}(function(t){"use strict";function e(t){d.Plugins[t].initialized||(d.Plugins[t].methods._setup.call(document),d.Plugins[t].initialized=!0)}function n(t,e,n,i){var s,r={raw:{}};i=i||{};for(s in i)i.hasOwnProperty(s)&&("classes"===t?(r.raw[i[s]]=e+"-"+i[s],r[i[s]]="."+e+"-"+i[s]):(r.raw[s]=i[s],r[s]=i[s]+"."+e));for(s in n)n.hasOwnProperty(s)&&("classes"===t?(r.raw[s]=n[s].replace(/{ns}/g,e),r[s]=n[s].replace(/{ns}/g,"."+e)):(r.raw[s]=n[s].replace(/.{ns}/g,""),r[s]=n[s].replace(/{ns}/g,e)));return r}function i(){d.windowWidth=d.$window.width(),d.windowHeight=d.$window.height(),w=u.startTimer(w,m,s)}function s(){for(var t in d.ResizeHandlers)d.ResizeHandlers.hasOwnProperty(t)&&d.ResizeHandlers[t].callback.call(window,d.windowWidth,d.windowHeight)}function r(){if(d.support.raf){d.window.requestAnimationFrame(r);for(var t in d.RAFHandlers)d.RAFHandlers.hasOwnProperty(t)&&d.RAFHandlers[t].callback.call(window)}}function o(t,e){return parseInt(t.priority)-parseInt(e.priority)}var a="undefined"!=typeof window?window:this,c=a.document,l=function(){this.Version="1.1.0",this.Plugins={},this.DontConflict=!1,this.Conflicts={fn:{}},this.ResizeHandlers=[],this.RAFHandlers=[],this.window=a,this.$window=t(a),this.document=c,this.$document=t(c),this.$body=null,this.windowWidth=0,this.windowHeight=0,this.fallbackWidth=1024,this.fallbackHeight=768,this.userAgent=window.navigator.userAgent||window.navigator.vendor||window.opera,this.isFirefox=/Firefox/i.test(this.userAgent),this.isChrome=/Chrome/i.test(this.userAgent),this.isSafari=/Safari/i.test(this.userAgent)&&!this.isChrome,this.isMobile=/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(this.userAgent),this.isIEMobile=/IEMobile/i.test(this.userAgent),this.isFirefoxMobile=this.isFirefox&&this.isMobile,this.transform=null,this.transition=null,this.support={file:!!(window.File&&window.FileList&&window.FileReader),history:!!(window.history&&window.history.pushState&&window.history.replaceState),matchMedia:!(!window.matchMedia&&!window.msMatchMedia),pointer:!!window.PointerEvent,raf:!(!window.requestAnimationFrame||!window.cancelAnimationFrame),touch:!!("ontouchstart"in window||window.DocumentTouch&&document instanceof window.DocumentTouch),transition:!1,transform:!1}},u={killEvent:function(t,e){try{t.preventDefault(),t.stopPropagation(),e&&t.stopImmediatePropagation()}catch(t){}},startTimer:function(t,e,n,i){return u.clearTimer(t),i?setInterval(n,e):setTimeout(n,e)},clearTimer:function(t,e){t&&(e?clearInterval(t):clearTimeout(t),t=null)},sortAsc:function(t,e){return parseInt(t,10)-parseInt(e,10)},sortDesc:function(t,e){return parseInt(e,10)-parseInt(t,10)},decodeEntities:function(t){var e=d.document.createElement("textarea");return e.innerHTML=t,e.value},parseQueryString:function(t){for(var e={},n=t.slice(t.indexOf("?")+1).split("&"),i=0;i<n.length;i++){var s=n[i].split("=");e[s[0]]=s[1]}return e}},d=new l,f=t.Deferred(),p={base:"{ns}",element:"{ns}-element"},h={namespace:".{ns}",beforeUnload:"beforeunload.{ns}",blur:"blur.{ns}",change:"change.{ns}",click:"click.{ns}",dblClick:"dblclick.{ns}",drag:"drag.{ns}",dragEnd:"dragend.{ns}",dragEnter:"dragenter.{ns}",dragLeave:"dragleave.{ns}",dragOver:"dragover.{ns}",dragStart:"dragstart.{ns}",drop:"drop.{ns}",error:"error.{ns}",focus:"focus.{ns}",focusIn:"focusin.{ns}",focusOut:"focusout.{ns}",input:"input.{ns}",keyDown:"keydown.{ns}",keyPress:"keypress.{ns}",keyUp:"keyup.{ns}",load:"load.{ns}",mouseDown:"mousedown.{ns}",mouseEnter:"mouseenter.{ns}",mouseLeave:"mouseleave.{ns}",mouseMove:"mousemove.{ns}",mouseOut:"mouseout.{ns}",mouseOver:"mouseover.{ns}",mouseUp:"mouseup.{ns}",panStart:"panstart.{ns}",pan:"pan.{ns}",panEnd:"panend.{ns}",resize:"resize.{ns}",scaleStart:"scalestart.{ns}",scaleEnd:"scaleend.{ns}",scale:"scale.{ns}",scroll:"scroll.{ns}",select:"select.{ns}",swipe:"swipe.{ns}",touchCancel:"touchcancel.{ns}",touchEnd:"touchend.{ns}",touchLeave:"touchleave.{ns}",touchMove:"touchmove.{ns}",touchStart:"touchstart.{ns}"};l.prototype.NoConflict=function(){d.DontConflict=!0;for(var e in d.Plugins)d.Plugins.hasOwnProperty(e)&&(t[e]=d.Conflicts[e],t.fn[e]=d.Conflicts.fn[e])},l.prototype.Plugin=function(i,s){return d.Plugins[i]=function(e,i){function s(n){var s,r,o,c="object"===t.type(n),l=this,u=t();for(n=t.extend(!0,{},i.defaults||{},c?n:{}),r=0,o=l.length;o>r;r++)if(s=l.eq(r),!a(s)){var d="__"+i.guid++,f=i.classes.raw.base+d,p=s.data(e+"-options"),h=t.extend(!0,{$el:s,guid:d,rawGuid:f,dotGuid:"."+f},n,"object"===t.type(p)?p:{});s.addClass(i.classes.raw.element).data(y,h),i.methods._construct.apply(s,[h].concat(Array.prototype.slice.call(arguments,c?1:0))),u=u.add(s)}for(r=0,o=u.length;o>r;r++)s=u.eq(r),i.methods._postConstruct.apply(s,[a(s)]);return l}function r(t){i.functions.iterate.apply(this,[i.methods._destruct].concat(Array.prototype.slice.call(arguments,1))),this.removeClass(i.classes.raw.element).removeData(y)}function a(t){return t.data(y)}function c(e){if(this instanceof t){var n=i.methods[e];return"object"!==t.type(e)&&e?n&&0!==e.indexOf("_")?i.functions.iterate.apply(this,[n].concat(Array.prototype.slice.call(arguments,1))):this:s.apply(this,arguments)}}function l(e){var n=i.utilities[e]||i.utilities._initialize||!1;return n?n.apply(window,Array.prototype.slice.call(arguments,"object"===t.type(e)?0:1)):void 0}function f(e){i.defaults=t.extend(!0,i.defaults,e||{})}function w(e){for(var n=this,i=0,s=n.length;s>i;i++){var r=n.eq(i),o=a(r)||{};"undefined"!==t.type(o.$el)&&e.apply(r,[o].concat(Array.prototype.slice.call(arguments,1)))}return n}var m="fs-"+e,y="fs"+e.replace(/(^|\s)([a-z])/g,function(t,e,n){return e+n.toUpperCase()});return i.initialized=!1,i.priority=i.priority||10,i.classes=n("classes",m,p,i.classes),i.events=n("events",e,h,i.events),i.functions=t.extend({getData:a,iterate:w},u,i.functions),i.methods=t.extend(!0,{_setup:t.noop,_construct:t.noop,_postConstruct:t.noop,_destruct:t.noop,_resize:!1,destroy:r},i.methods),i.utilities=t.extend(!0,{_initialize:!1,_delegate:!1,defaults:f},i.utilities),i.widget&&(d.Conflicts.fn[e]=t.fn[e],t.fn[y]=c,d.DontConflict||(t.fn[e]=t.fn[y])),d.Conflicts[e]=t[e],t[y]=i.utilities._delegate||l,d.DontConflict||(t[e]=t[y]),i.namespace=e,i.namespaceClean=y,i.guid=0,i.methods._resize&&(d.ResizeHandlers.push({namespace:e,priority:i.priority,callback:i.methods._resize}),d.ResizeHandlers.sort(o)),i.methods._raf&&(d.RAFHandlers.push({namespace:e,priority:i.priority,callback:i.methods._raf}),d.RAFHandlers.sort(o)),i}(i,s),f.then(function(){e(i)}),d.Plugins[i]};var w=null,m=20;return d.$window.on("resize.fs",i),i(),r(),t(function(){d.$body=t("body"),f.resolve(),d.support.nativeMatchMedia=d.support.matchMedia&&!t("html").hasClass("no-matchmedia")}),h.clickTouchStart=h.click+" "+h.touchStart,function(){var t,e={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"otransitionend",transition:"transitionend"},n=["transition","-webkit-transition"],i={transform:"transform",MozTransform:"-moz-transform",OTransform:"-o-transform",msTransform:"-ms-transform",webkitTransform:"-webkit-transform"},s="transitionend",r="",o="",a=document.createElement("div");for(t in e)if(e.hasOwnProperty(t)&&t in a.style){s=e[t],d.support.transition=!0;break}h.transitionEnd=s+".{ns}";for(t in n)if(n.hasOwnProperty(t)&&n[t]in a.style){r=n[t];break}d.transition=r;for(t in i)if(i.hasOwnProperty(t)&&i[t]in a.style){d.support.transform=!0,o=i[t];break}d.transform=o}(),window.Formstone=d,d});