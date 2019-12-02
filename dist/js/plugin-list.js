!function(e){var t={};function n(r){if(t[r])return t[r].exports;var i=t[r]={i:r,l:!1,exports:{}};return e[r].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)n.d(r,i,function(t){return e[t]}.bind(null,i));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=0)}([function(e,t,n){e.exports=n(1)},function(e,t){function n(e){return(n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function i(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function o(e,t,n){return t&&i(e.prototype,t),n&&i(e,n),e}function l(e,t){return!t||"object"!==n(t)&&"function"!=typeof t?function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e):t}function c(e){return(c=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function a(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&s(e,t)}function s(e,t){return(s=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}var u=wp.element,m=u.render,p=u.Component,d=wp.i18n,f=d.__,g=d.sprintf,_=function(e){function t(e){var n;return r(this,t),(n=l(this,c(t).call(this,e))).state={loading:!0,title:"",url:"",install:"",description:"",notes:"",activated:!1,src:"",error:!1,priority:0},n}return a(t,e),o(t,[{key:"componentDidMount",value:function(){var e=this;wp.apiFetch({path:"kunoichi/v1/plugins/recommendation/"+this.props.slug}).then((function(t){e.setState(t)})).catch((function(t){e.setState({title:e.props.slug,error:!0,description:t.message})})).finally((function(){e.setState({loading:!1})}))}},{key:"render",value:function(){var e=this.state,t=e.loading,n=e.activated,r=e.error,i=e.url,o=e.install,l=e.priority,c=["plugins-recommender-list__item"];this.state.loading&&c.push("loading"),n&&c.push("plugins-recommender-list__activated");var a=["plugins-recommender-list__note"],s="";t||this.state.notes?t||(s=this.state.notes):(a.push("plugins-recommender-list__note--empty"),s=f("No reason provided.","pr"));var u="";t||(u=this.state.description.slice(0,200)+"...");var m=["button-primary"];n&&m.push("disabled");var p="",d=["plugins-recommender-list__subtitle"];return 90<=l?(p=f("Required","pr"),d.push("plugins-recommender-list__subtitle--required")):50<=l?(p=f("Integrated","pr"),d.push("plugins-recommender-list__subtitle--integrated")):p=f("Recommended","pr"),React.createElement("div",{className:c.join(" ")},React.createElement("div",{className:"plugins-recommender-list__inner"},this.state.loading||this.state.error?React.createElement("div",{className:"plugins-recommender-list__placeholder"}," "):React.createElement("img",{src:this.state.src,alt:"",className:"plugins-recommender-list__img"}),React.createElement("div",{className:"plugins-recommender-list__body"},React.createElement("h3",{className:"plugins-recommender-list__title"},this.state.title),React.createElement("p",{className:"plugins-recommender-list__desc"},u),React.createElement("h4",{className:d.join(" ")},t?"":p),React.createElement("p",{className:a.join(" ")},s),r||t?null:React.createElement("p",{className:"plugins-recommender-list__actions"},React.createElement("a",{href:o,className:m.join(" ")},f(n?"Activated":"Install","pr")),React.createElement("a",{href:i,rel:"noopener noreferrer",target:"_blank",className:"button"},f("Detail","pr"))))))}}]),t}(p),y=function(e){function t(e){var n;return r(this,t),(n=l(this,c(t).call(this,e))).plugins=RecommenderList.plugins,n}return a(t,e),o(t,[{key:"render",value:function(){var e=this.plugins;return e.length?React.createElement("div",{className:"plugins-recommender-list"},React.createElement("p",{className:"description plugins-recommender-list__desc"},g(f("Recommended Plugins: %d","plugins-recommender"),e.length)),React.createElement("div",{className:"plugins-recommender-list__grid"},e.map((function(e){return React.createElement(_,{key:e,slug:e})})))):React.createElement("div",{className:"notice notice-error plugins-recommender-list__error"},f("No plugin is recommended.","plugins-recommender"))}}]),t}(p);m(React.createElement(y,null),document.getElementById("plugin-recommender-list"))}]);