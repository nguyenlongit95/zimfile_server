(this.webpackJsonpzimfiles=this.webpackJsonpzimfiles||[]).push([[2],{143:function(e,n,t){"use strict";t.d(n,"a",(function(){return c}));var a=t(59),r=t(182),c=Object(a.a)([function(e){return e.login||r.a}],(function(e){return e}))},152:function(e,n,t){"use strict";t.d(n,"b",(function(){return E})),t.d(n,"a",(function(){return x}));var a=t(242),r=t(105),c=t(150),o=t(358),i=t(178),u=t(36);function l(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};return 0===Object.keys(e).length?function(e){return e}:Object(u.c)(Object(i.a)({},e))}var s=t(189),f=t(69),d=t(191),m=t.n(d),b=t(190),g={key:"root",storage:m.a,stateReconciler:b.a,whitelist:[]},p=Object(f.g)(g,l()),h=Object(o.a)({}),j=h.run,O=[h],v=[Object(c.a)({createReducer:l,runSaga:j})],E=Object(r.a)({reducer:p,middleware:[].concat(Object(a.a)(Object(r.c)({serializableCheck:{ignoredActions:[f.a,f.f,f.b,f.c,f.d,f.e]}})),O),devTools:!1,enhancers:v});E.subscribe((function(){var e,n=null===(e=E.getState().login)||void 0===e?void 0:e.userInfo.accessToken;n&&s.a.setToken(n)}));var x=Object(f.h)(E)},181:function(e,n,t){"use strict";var a=t(178),r=t(352),c=t.n(r),o=t(189),i=t(152),u=c.a.create({baseURL:"http://3.12.146.14/api/",headers:{Accept:"application/json","Content-Type":"application/json"}});u.interceptors.request.use((function(e){var n=Object(a.a)({},e),t=o.a.token;return n.headers.common.Authorization=t?"Bearer ".concat(t):"",n})),u.interceptors.response.use((function(e){return e.data.data}),(function(e){throw e&&e.response&&401===e.response.status&&i.b.dispatch({type:"login/LogoutSuccess"}),e})),n.a=u},182:function(e,n,t){"use strict";t.d(n,"a",(function(){return j})),t.d(n,"b",(function(){return v})),t.d(n,"c",(function(){return k}));var a=t(246),r=t(229),c=t(106),o=t.n(c),i=t(80),u=t(230),l=o.a.mark(d),s=o.a.mark(m),f=o.a.mark(b);function d(e){var n,t;return o.a.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:return a.prev=0,n={email:e.payload.email,password:e.payload.password},a.next=4,Object(i.a)(u.b,n);case 4:return t=a.sent,a.next=7,Object(i.b)(v.LoginSuccess(t));case 7:a.next=13;break;case 9:return a.prev=9,a.t0=a.catch(0),a.next=13,Object(i.b)(v.LoginError(a.t0.response.status));case 13:case"end":return a.stop()}}),l,null,[[0,9]])}function m(){return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,e.next=3,Object(i.a)(u.c);case 3:return e.next=5,Object(i.b)(v.LogoutSuccess());case 5:e.next=11;break;case 7:return e.prev=7,e.t0=e.catch(0),e.next=11,Object(i.b)(v.LogoutError());case 11:case"end":return e.stop()}}),s,null,[[0,7]])}function b(){return o.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,Object(i.d)(v.LoginRequest,d);case 2:return e.next=4,Object(i.d)(v.LogoutRequest,m);case 4:case"end":return e.stop()}}),f)}var g=t(69),p=t(191),h=t.n(p),j={remember:!1,loadingLogin:!1,userInfo:{},error:0,errorLogout:!1},O=Object(a.a)({name:"login",initialState:j,reducers:{LoginRequest:function(e,n){e.loadingLogin=!0,e.remember=n.payload.remember,e.error=0},LoginSuccess:function(e,n){e.loadingLogin=!1,e.userInfo=n.payload,e.error=0},LoginError:function(e,n){e.loadingLogin=!1,e.error=n.payload,e.userInfo={}},LogoutRequest:function(e,n){e.errorLogout=!1},LogoutSuccess:function(e,n){e.userInfo={},e.errorLogout=!1},LogoutError:function(e,n){e.errorLogout=!0}}}),v=O.actions,E={key:"login",storage:h.a,whitelist:["userInfo"]},x=Object(g.g)(E,O.reducer),k=function(){return Object(r.a)({key:O.name,reducer:x}),Object(r.b)({key:O.name,saga:b}),{actions:O.actions}}},189:function(e,n,t){"use strict";t.d(n,"a",(function(){return a}));var a={token:"",exp:0,logout:[],setToken:function(e){this.token=e},setExpires:function(e){this.exp=e}}},229:function(e,n,t){"use strict";t.d(n,"a",(function(){return r})),t.d(n,"b",(function(){return c}));var a=t(150);function r(e){return Object(a.b)(e)}function c(e){return Object(a.c)(e)}},230:function(e,n,t){"use strict";t.d(n,"b",(function(){return r})),t.d(n,"c",(function(){return c})),t.d(n,"a",(function(){return o}));var a=t(181);function r(e){return a.a.post("login",e)}function c(){return a.a.get("logout")}function o(e){return a.a.post("change-password",e)}},237:function(e){e.exports=JSON.parse('{"title":"welcome"}')},240:function(e,n,t){"use strict";t.d(n,"a",(function(){return r})),t.d(n,"b",(function(){return c}));var a=t(155);a.b.config({duration:2,maxCount:3});var r=function(e){a.b.error(e)},c=function(e){a.b.success(e)}},246:function(e,n,t){"use strict";t.d(n,"a",(function(){return r}));var a=t(105),r=function(e){return Object(a.b)(e)}},321:function(e,n,t){"use strict";n.a=t.p+"static/media/logo512.dcbc423a.png"},614:function(e,n,t){"use strict";t.r(n);t(367),t(377);var a,r,c,o,i,u,l=t(0),s=t.n(l),f=t(35),d=t(46),m=(t(567),t(65)),b=t(361),g=t(156),p=t(32),h=t(157),j=t(61),O=Object(j.a)(a||(a=Object(m.a)(["\n  html,\n  body {\n    height: 100%;\n    width: 100%;\n  }\n\n  body {\n    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;\n  }\n\n  #root {\n    min-height: 100%;\n    min-width: 100%;\n  }\n\n  p,\n  label {\n    font-family: Georgia, Times, 'Times New Roman', serif;\n    line-height: 1.5em;\n  }\n\n  input, select {\n    font-family: inherit;\n    font-size: inherit;\n  }\n"]))),v=t(90),E=Object(v.a)((function(){return Promise.all([t.e(0),t.e(5),t.e(6)]).then(t.bind(null,696))}),(function(e){return e.HomePage})),x=Object(v.a)((function(){return Promise.all([t.e(0),t.e(1),t.e(10)]).then(t.bind(null,698))}),(function(e){return e.LoginPage})),k=Object(v.a)((function(){return Promise.all([t.e(0),t.e(1),t.e(7)]).then(t.bind(null,703))}),(function(e){return e.ChangePasswordPage})),w=Object(v.a)((function(){return t.e(9).then(t.bind(null,706))}),(function(e){return e.NotFoundPage})),y=t(621),L=t(619),I=t(240),S=t(321),T=t(618),C=t(616),P=t(620),F=t(622),R=t(623),z=t(143),q=t(182);function A(){var e=Object(p.g)(),n=Object(d.c)(),t=Object(q.c)().actions,a=Object(d.d)(z.a),r=a.userInfo,c=a.errorLogout,o=function(){n(t.LogoutRequest())};Object(l.useEffect)((function(){c&&Object(I.a)("Something went wrong. Please try again!")}),[c]);return s.a.createElement(Z,null,s.a.createElement(B,{onClick:function(){e.push("/home")}},s.a.createElement(J,{src:S.a}),s.a.createElement(D,null,"Zim Studio")),s.a.createElement(G,null),function(){var e,n;return s.a.createElement(T.a,{placement:"topCenter",overlay:s.a.createElement(s.a.Fragment,null,s.a.createElement(C.a,null,s.a.createElement(C.a.Item,{key:"info"},s.a.createElement(h.b,{to:"change-password"},s.a.createElement("span",null,s.a.createElement(F.a,null),"\xa0","Change Password"))),s.a.createElement(C.a.Item,{key:"logout",onClick:o},s.a.createElement("span",null,s.a.createElement(R.a,null),"\xa0","Logout"))))},s.a.createElement("span",{className:"ant-dropdown-link",style:{color:"white",cursor:"pointer"}},s.a.createElement(P.a,{style:{backgroundColor:"#0d4b82",marginRight:3}},null===(e=r.profiles)||void 0===e?void 0:e.name[0]),"\xa0",null===(n=r.profiles)||void 0===n?void 0:n.name))}())}var N,H,Z=Object(j.c)(L.a.Header)(r||(r=Object(m.a)(["\n  position: fixed;\n  z-index: 1;\n  width: 100%;\n  padding: 0 25px;\n  display: flex;\n  align-items: center;\n"]))),B=j.c.a(c||(c=Object(m.a)(["\n  display: flex;\n  flex-direction: row;\n  height: 100%;\n  align-items: center;\n"]))),J=j.c.img(o||(o=Object(m.a)(["\n  width: 40px;\n  height: 40px;\n  object-fit: contain;\n"]))),D=j.c.div(i||(i=Object(m.a)(["\n  color: #fff;\n  padding-left: 10px;\n  font-size: large;\n  font-weight: bold;\n"]))),G=j.c.div(u||(u=Object(m.a)(["\n  flex: 1;\n  margin-left: 30px;\n"])));function M(e){var n=e.children,t=Object(b.a)(e,["children"]),a=Object(d.d)(z.a).userInfo;return s.a.createElement(p.b,Object.assign({},t,{render:function(e){var t=e.location;return a.accessToken?n:s.a.createElement(p.a,{to:{pathname:"/",state:{from:t}}})}}))}function U(){var e=Object(y.a)().i18n,n=Object(d.d)(z.a).userInfo;return s.a.createElement(h.a,null,s.a.createElement(g.a,{titleTemplate:"%s - ZimFiles",defaultTitle:"ZimFiles",htmlAttributes:{lang:e.language}},s.a.createElement("meta",{name:"description",content:"A ZimFiles application"})),s.a.createElement(V,null,n.accessToken&&s.a.createElement(A,null),s.a.createElement(K,{ischeck:n.accessToken?"1":"2"},s.a.createElement(p.d,null,s.a.createElement(p.b,{exact:!0,path:"/",component:x}),s.a.createElement(M,{exact:!0,path:"/home"},s.a.createElement(E,null)),s.a.createElement(M,{exact:!0,path:"/home/:folderId"},s.a.createElement(E,null)),s.a.createElement(M,{exact:!0,path:"/home/image/:ImageFolderId"},s.a.createElement(E,null)),s.a.createElement(M,{path:"/change-password"},s.a.createElement(k,null)),s.a.createElement(p.b,{component:w})))),s.a.createElement(O,null))}var V=Object(j.c)(L.a)(N||(N=Object(m.a)(["\n  min-height: 100vh;\n  display: flex;\n"]))),K=Object(j.c)(L.a.Content)(H||(H=Object(m.a)(["\n  display: flex;\n  flex: 1;\n  flex-direction: column;\n  margin-top: ","px;\n  background-color: #dddddd;\n"])),(function(e){return"1"===e.ischeck?64:0})),Q=t(152),W=function(e){e&&e instanceof Function&&t.e(11).then(t.bind(null,693)).then((function(n){var t=n.getCLS,a=n.getFID,r=n.getFCP,c=n.getLCP,o=n.getTTFB;t(e),a(e),r(e),c(e),o(e)}))},X=t(356),Y=t(362),$=t(154),_=t(359),ee=t(237),ne={},te={en:{translation:ee}};!function e(n){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:ne,a=arguments.length>2?arguments[2]:void 0;Object.keys(n).forEach((function(r){var c=a?"".concat(a,".").concat(r):r;"object"===typeof n[r]?(t[r]={},e(n[r],t[r],c)):t[r]=c}))}(ee);Y.a.use($.e).use(_.a).init({resources:te,fallbackLng:"en",debug:!1,interpolation:{escapeValue:!1}});var ae=document.getElementById("root");f.render(l.createElement(d.a,{store:Q.b},l.createElement(X.a,{loading:null,persistor:Q.a},l.createElement(g.b,null,l.createElement(l.StrictMode,null,l.createElement(U,null))))),ae),W()},90:function(e,n,t){"use strict";t.d(n,"a",(function(){return c}));var a=t(0),r=t.n(a),c=function(e,n){var t=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{fallback:null},c=e;n&&(c=function(){return e().then((function(e){return{default:n(e)}}))});var o=Object(a.lazy)(c);return function(e){return r.a.createElement(a.Suspense,{fallback:t.fallback},r.a.createElement(o,e))}}}},[[614,3,4]]]);