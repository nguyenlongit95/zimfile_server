(this.webpackJsonpzimfiles=this.webpackJsonpzimfiles||[]).push([[2],{103:function(e,t,r){"use strict";r.d(t,"a",(function(){return o}));var n=r(0),a=r.n(n),o=function(e,t){var r=arguments.length>2&&void 0!==arguments[2]?arguments[2]:{fallback:null},o=e;t&&(o=function(){return e().then((function(e){return{default:t(e)}}))});var c=Object(n.lazy)(o);return function(e){return a.a.createElement(n.Suspense,{fallback:r.fallback},a.a.createElement(c,e))}}},122:function(e,t,r){"use strict";r.d(t,"b",(function(){return K})),r.d(t,"a",(function(){return W})),r.d(t,"c",(function(){return X}));var n=r(70),a=r(209),o=r(136),c=r(164),i=r(24),s=r.n(i),u=r(9),l=r(45);function d(e){return l.b.get("list-directories?parent_id=".concat(e.parent_id))}function f(){return l.b.get("time-server")}function p(e){return l.b.post("create-director",e)}function b(e){return l.b.get("jobs-in-dir?dir_id=".concat(e.dir_id))}function g(){return l.b.get("editor/get-job")}function m(e){return l.b.post("editor/confirm-job",e)}function v(e){return l.b.get("editor/cancel-job?dir_id=".concat(e.dir_id))}function h(){return l.b.get("qc/list-jobs?status=3")}function j(e){return l.b.post("qc/check-confirm-job",e)}function E(){return l.b.get("list-dir-in-done")}function O(e){return l.b.get("qc/get-job-to-check?job_id=".concat(e))}function y(){return l.b.get("editor/get-notifications")}var x=s.a.mark(R),k=s.a.mark(P),w=s.a.mark(Q),C=s.a.mark(N),S=s.a.mark(T),J=s.a.mark(A),D=s.a.mark(z),F=s.a.mark(U),_=s.a.mark(M),L=s.a.mark(H),I=s.a.mark(V),q=s.a.mark(Z);function R(e){var t,r;return s.a.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return n.prev=0,t={parent_id:e.payload},n.next=4,Object(u.a)(d,t);case 4:return r=n.sent,n.next=7,Object(u.b)(W.getFolderSuccess(r));case 7:n.next=13;break;case 9:return n.prev=9,n.t0=n.catch(0),n.next=13,Object(u.b)(W.getFolderError(n.t0.response.status));case 13:case"end":return n.stop()}}),x,null,[[0,9]])}function P(e){var t,r,a,o,i,l,d,b,g,m;return s.a.wrap((function(s){for(;;)switch(s.prev=s.next){case 0:return s.prev=0,s.next=3,Object(u.c)((function(e){var t;return null===(t=e.homePage)||void 0===t?void 0:t.dataDirect}));case 3:if(t=s.sent,1!==e.payload.level){s.next=25;break}return s.next=7,Object(u.a)(f);case 7:if(r=s.sent,a=new Date(r),o="dir_"+("0"+(a.getMonth()+1)).slice(-2)+("0"+a.getDate()).slice(-2)+a.getUTCFullYear(),!t.directors.filter((function(e){return e.nas_dir===o})).length){s.next=16;break}return s.next=14,Object(u.b)(W.createFloderError(1));case 14:s.next=23;break;case 16:return i={director:o,level:e.payload.level,parent_id:e.payload.parentID,path:e.payload.path,type:e.payload.type},s.next=19,Object(u.a)(p,i);case 19:return l=s.sent,d=Object(n.a)(Object(n.a)({},t),{},{directors:[l].concat(Object(c.a)(t.directors))}),s.next=23,Object(u.b)(W.createFolderSucess(d));case 23:s.next=32;break;case 25:return b={director:e.payload.director,level:e.payload.level,parent_id:e.payload.parentID,path:e.payload.path,type:e.payload.type,customer_note:e.payload.customer_note},s.next=28,Object(u.a)(p,b);case 28:return g=s.sent,m=Object(n.a)(Object(n.a)({},t),{},{directors:[g].concat(Object(c.a)(t.directors))}),s.next=32,Object(u.b)(W.createFolderSucess(m));case 32:s.next=38;break;case 34:return s.prev=34,s.t0=s.catch(0),s.next=38,Object(u.b)(W.createFloderError(s.t0.response.status));case 38:case"end":return s.stop()}}),k,null,[[0,34]])}function Q(e){var t,r;return s.a.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return n.prev=0,t={dir_id:e.payload},n.next=4,Object(u.a)(b,t);case 4:return r=n.sent,n.next=7,Object(u.b)(W.getImageSuccess(r));case 7:n.next=13;break;case 9:return n.prev=9,n.t0=n.catch(0),n.next=13,Object(u.b)(W.getImageError(n.t0.response.status));case 13:case"end":return n.stop()}}),w,null,[[0,9]])}function N(){var e;return s.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.prev=0,t.next=3,Object(u.a)(g);case 3:return e=t.sent,t.next=6,Object(u.b)(W.getJobEditorSucess(e));case 6:t.next=12;break;case 8:return t.prev=8,t.t0=t.catch(0),t.next=12,Object(u.b)(W.getJobEditorError(t.t0.response.status));case 12:case"end":return t.stop()}}),C,null,[[0,8]])}function T(e){var t;return s.a.wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return r.prev=0,t={dir_id:e.payload},r.next=4,Object(u.a)(m,t);case 4:return r.next=6,Object(u.b)(W.sendJobEditorSucess());case 6:r.next=12;break;case 8:return r.prev=8,r.t0=r.catch(0),r.next=12,Object(u.b)(W.sendJobEditorError(r.t0.response.status));case 12:case"end":return r.stop()}}),S,null,[[0,8]])}function A(e){var t;return s.a.wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return r.prev=0,t={dir_id:e.payload},r.next=4,Object(u.a)(v,t);case 4:return r.next=6,Object(u.b)(W.cancelJobEditorSucess());case 6:r.next=12;break;case 8:return r.prev=8,r.t0=r.catch(0),r.next=12,Object(u.b)(W.cancelJobEditorError(r.t0.response.status));case 12:case"end":return r.stop()}}),J,null,[[0,8]])}function z(){var e;return s.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.prev=0,t.next=3,Object(u.a)(h);case 3:return e=t.sent,t.next=6,Object(u.b)(W.getQCSucess(e));case 6:t.next=12;break;case 8:return t.prev=8,t.t0=t.catch(0),t.next=12,Object(u.b)(W.getQCError(t.t0.response.status));case 12:case"end":return t.stop()}}),D,null,[[0,8]])}function U(e){var t,r,n;return s.a.wrap((function(a){for(;;)switch(a.prev=a.next){case 0:return a.prev=0,t={dir_id:e.payload.id,status:e.payload.status,note:e.payload.note,qty:e.payload.qty},a.next=4,Object(u.c)((function(e){var t;return null===(t=e.homePage)||void 0===t?void 0:t.dataQC}));case 4:return r=a.sent,a.next=7,Object(u.a)(j,t);case 7:return n=r.filter((function(t){return t.id!==e.payload.id})),a.next=10,Object(u.b)(W.confirmJobQCSucess(n));case 10:a.next=16;break;case 12:return a.prev=12,a.t0=a.catch(0),a.next=16,Object(u.b)(W.confirmJobQCError(a.t0.response.status));case 16:case"end":return a.stop()}}),F,null,[[0,12]])}function M(){var e;return s.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.prev=0,t.next=3,Object(u.a)(E);case 3:return e=t.sent,t.next=6,Object(u.b)(W.getListDownloadSuccess(e));case 6:t.next=12;break;case 8:return t.prev=8,t.t0=t.catch(0),t.next=12,Object(u.b)(W.getListDownloadError(t.t0.response.status));case 12:case"end":return t.stop()}}),_,null,[[0,8]])}function H(e){var t,r;return s.a.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return n.prev=0,n.next=3,Object(u.a)(O,e.payload);case 3:return t=n.sent,n.next=6,Object(u.b)(W.checkJobSuccess(t));case 6:n.next=13;break;case 8:return n.prev=8,n.t0=n.catch(0),console.log(n.t0),n.next=13,Object(u.b)(W.checkJobError(null===n.t0||void 0===n.t0||null===(r=n.t0.response)||void 0===r?void 0:r.status));case 13:case"end":return n.stop()}}),L,null,[[0,8]])}function V(){var e,t;return s.a.wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return r.prev=0,r.next=3,Object(u.a)(y);case 3:return e=r.sent,r.next=6,Object(u.b)(W.getNotificationSuccess(e.notifications));case 6:r.next=12;break;case 8:return r.prev=8,r.t0=r.catch(0),r.next=12,Object(u.b)(W.getNotificationError(null===r.t0||void 0===r.t0||null===(t=r.t0.response)||void 0===t?void 0:t.status));case 12:case"end":return r.stop()}}),I,null,[[0,8]])}function Z(){return s.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,Object(u.d)(W.getFolderRequest,R);case 2:return e.next=4,Object(u.d)(W.createFolderRequest,P);case 4:return e.next=6,Object(u.d)(W.getImageRequest,Q);case 6:return e.next=8,Object(u.d)(W.getJobEditorRequest,N);case 8:return e.next=10,Object(u.d)(W.sendJobEditorRequest,T);case 10:return e.next=12,Object(u.d)(W.cancelJobEditorRequest,A);case 12:return e.next=14,Object(u.d)(W.getQCRequest,z);case 14:return e.next=16,Object(u.d)(W.confirmJobQCRequest,U);case 16:return e.next=18,Object(u.d)(W.getListDownloadRequest,M);case 18:return e.next=20,Object(u.d)(W.checkJobRequest,H);case 20:return e.next=22,Object(u.d)(W.getNotificationRequest,V);case 22:case"end":return e.stop()}}),q)}var B={id:0,user_id:0,nas_dir:"",vps_dir:"",created_at:"",updated_at:"",level:0,parent_id:0,path:[],type:1},G={id:0,user_id:0,nas_dir:"",vps_dir:"",created_at:"",updated_at:"",level:0,parent_id:0,path:"",type:1,editor_id:0,note:"",status:0,path_job_lan:"",path_job_online:"",customer_note:""},K={loadingDirect:!1,dataDirect:{directors:[],parent_director:B},dataImage:{jobs:[],parent_director:B},errorDirect:0,idFolderSelected:0,loadingCreate:!1,dataCreate:B,errorCreate:0,levelFolder:1,visibleModalCreate:!1,fileProgress:[],loadingEditor:!1,dataEditor:G,errorEditor:0,loadingQC:!1,loadingConfirmJob:!1,dataQC:[],errorQC:0,typeAction:"0",listDownload:[],listDownloadLoading:!1,listDownloadError:0,nameSelectedDownload:"",createFolderSuccess:!1,errorCheckJob:0,notification:"",loadingNoti:!1,errorNoti:0},Y=Object(a.a)({name:"homePage",initialState:K,reducers:{getFolderRequest:function(e,t){e.loadingDirect=!0,e.errorDirect=0},getFolderSuccess:function(e,t){e.loadingDirect=!1,e.dataDirect=t.payload,e.levelFolder=t.payload.parent_director?t.payload.parent_director.level+1:1},getFolderError:function(e,t){e.loadingDirect=!1,e.errorDirect=t.payload},selectFolder:function(e,t){e.idFolderSelected=t.payload},createFolderRequest:function(e,t){e.loadingCreate=!0,e.errorCreate=0,e.createFolderSuccess=!1},createFolderSucess:function(e,t){e.loadingCreate=!1,e.dataDirect=t.payload,e.visibleModalCreate=!1,e.createFolderSuccess=!0},createFloderError:function(e,t){e.loadingCreate=!1,e.errorCreate=t.payload,e.createFolderSuccess=!1},setVisibleModalCreate:function(e,t){e.visibleModalCreate=t.payload},getImageRequest:function(e,t){e.loadingDirect=!0,e.errorDirect=0},getImageSuccess:function(e,t){e.loadingDirect=!1,e.dataImage=t.payload,e.levelFolder=t.payload.parent_director?t.payload.parent_director.level+1:1},getImageError:function(e,t){e.loadingDirect=!1,e.errorDirect=t.payload},setUploadFile:function(e,t){var r=e.fileProgress.concat(t.payload);e.fileProgress=r},setUploadFileProgress:function(e,t){var r=e.fileProgress.map((function(e){return e.id===t.payload.id&&(e.progress=t.payload.progress),e}));e.fileProgress=r},UploadFileSuccess:function(e,t){var r=!1,a=e.fileProgress.map((function(n){return n.id===t.payload.id&&(n.type="success",n.idFolderSelected===e.idFolderSelected&&(r=!0)),n}));if(e.fileProgress=a,r){var o=t.payload.data.concat(e.dataImage.jobs),c=Object(n.a)(Object(n.a)({},e.dataImage),{},{jobs:o});e.dataImage=c}},UploadFileDone:function(e,t){e.fileProgress=e.fileProgress.filter((function(e){return t.payload!==e.id}))},UploadFileError:function(e,t){var r=e.fileProgress.map((function(e){return e.id===t.payload&&(e.type="exception",e.id=0),e}));e.fileProgress=r},UpdateFile:function(e,t){e.fileProgress=t.payload},getJobEditorRequest:function(e,t){e.loadingEditor=!0,e.errorEditor=0},getJobEditorSucess:function(e,t){e.loadingEditor=!1,e.dataEditor=t.payload,localStorage.setItem("dataEditor",JSON.stringify(t.payload))},getJobEditorError:function(e,t){e.loadingEditor=!1,e.errorEditor=t.payload},sendJobEditorRequest:function(e,t){e.loadingEditor=!0,e.errorEditor=0},sendJobEditorSucess:function(e,t){e.loadingEditor=!1,e.dataEditor=G,localStorage.setItem("dataEditor",JSON.stringify(G))},sendJobEditorError:function(e,t){e.loadingEditor=!1,e.errorEditor=t.payload},cancelJobEditorRequest:function(e,t){e.loadingEditor=!0,e.errorEditor=0},cancelJobEditorSucess:function(e,t){e.loadingEditor=!1,e.dataEditor=G,localStorage.setItem("dataEditor",JSON.stringify(G))},cancelJobEditorError:function(e,t){e.loadingEditor=!1,e.errorEditor=t.payload},getQCRequest:function(e,t){e.loadingQC=!0,e.errorQC=0},getQCSucess:function(e,t){e.loadingQC=!1,e.dataQC=t.payload},getQCError:function(e,t){e.loadingQC=!1,e.errorQC=t.payload},confirmJobQCRequest:function(e,t){e.loadingConfirmJob=!0,e.errorQC=0},confirmJobQCSucess:function(e,t){e.loadingConfirmJob=!1,e.dataQC=t.payload},confirmJobQCError:function(e,t){e.loadingConfirmJob=!1,e.errorQC=t.payload},changeTypeAction:function(e,t){e.typeAction=t.payload},getListDownloadRequest:function(e,t){e.listDownloadLoading=!0,e.listDownloadError=0},getListDownloadSuccess:function(e,t){e.listDownloadLoading=!1,e.listDownload=t.payload},getListDownloadError:function(e,t){e.listDownloadLoading=!1,e.listDownloadError=t.payload},chooseDownload:function(e,t){e.nameSelectedDownload=t.payload},checkJobRequest:function(e,t){e.loadingConfirmJob=!0,e.errorCheckJob=0},checkJobSuccess:function(e,t){e.loadingConfirmJob=!1,e.dataQC=e.dataQC.map((function(e){return e.id===t.payload.id&&(e.qc_id=t.payload.qc_id),e}))},checkJobError:function(e,t){e.loadingConfirmJob=!1,e.errorCheckJob=t.payload},getNotificationRequest:function(e){e.loadingNoti=!0,e.errorNoti=0},getNotificationSuccess:function(e,t){e.loadingNoti=!1,e.notification=t.payload},getNotificationError:function(e,t){e.loadingNoti=!1,e.errorNoti=t.payload},resetValue:function(e){e.loadingDirect=!1,e.dataDirect={directors:[],parent_director:B},e.dataImage={jobs:[],parent_director:B},e.errorDirect=0,e.idFolderSelected=0,e.loadingCreate=!1,e.dataCreate=B,e.errorCreate=0,e.levelFolder=1,e.visibleModalCreate=!1,e.fileProgress=[],e.loadingEditor=!1,e.dataEditor=G,e.errorEditor=0,e.loadingQC=!1,e.loadingConfirmJob=!1,e.dataQC=[],e.errorQC=0,e.typeAction="0",e.listDownload=[],e.listDownloadLoading=!1,e.listDownloadError=0,e.nameSelectedDownload="",e.createFolderSuccess=!1,e.errorCheckJob=0,e.notification="",e.loadingNoti=!1,e.errorNoti=0}}}),W=Y.actions,X=function(){return Object(o.a)({key:Y.name,reducer:Y.reducer}),Object(o.b)({key:Y.name,saga:Z}),{actions:Y.actions}}},132:function(e,t,r){"use strict";r.d(t,"a",(function(){return j})),r.d(t,"b",(function(){return O})),r.d(t,"c",(function(){return k}));var n=r(209),a=r(136),o=r(24),c=r.n(o),i=r(9),s=r(122),u=r(263),l=c.a.mark(p),d=c.a.mark(b),f=c.a.mark(g);function p(e){var t,r;return c.a.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return n.prev=0,t={email:e.payload.email,password:e.payload.password},n.next=4,Object(i.a)(u.b,t);case 4:return r=n.sent,n.next=7,Object(i.b)(O.LoginSuccess(r));case 7:n.next=13;break;case 9:return n.prev=9,n.t0=n.catch(0),n.next=13,Object(i.b)(O.LoginError(n.t0.response.status));case 13:case"end":return n.stop()}}),l,null,[[0,9]])}function b(){return c.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,e.next=3,Object(i.a)(u.c);case 3:return e.next=5,Object(i.b)(O.LogoutSuccess());case 5:return e.next=7,Object(i.b)(s.a.resetValue());case 7:e.next=13;break;case 9:return e.prev=9,e.t0=e.catch(0),e.next=13,Object(i.b)(O.LogoutError());case 13:case"end":return e.stop()}}),d,null,[[0,9]])}function g(){return c.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,Object(i.d)(O.LoginRequest,p);case 2:return e.next=4,Object(i.d)(O.LogoutRequest,b);case 4:case"end":return e.stop()}}),f)}var m=r(80),v=r(219),h=r.n(v),j={remember:!1,loadingLogin:!1,userInfo:{},error:0,errorLogout:!1},E=Object(n.a)({name:"login",initialState:j,reducers:{LoginRequest:function(e,t){e.loadingLogin=!0,e.remember=t.payload.remember,e.error=0},LoginSuccess:function(e,t){e.loadingLogin=!1,e.userInfo=t.payload,e.error=0,localStorage.setItem("user",JSON.stringify(t.payload))},LoginError:function(e,t){e.loadingLogin=!1,e.error=t.payload,e.userInfo={}},LogoutRequest:function(e,t){e.errorLogout=!1},LogoutSuccess:function(e,t){e.userInfo={},e.errorLogout=!1,e.error=0,localStorage.removeItem("user")},LogoutError:function(e,t){e.errorLogout=!0}}}),O=E.actions,y={key:"login",storage:h.a,whitelist:[]},x=Object(m.g)(y,E.reducer),k=function(){return Object(a.a)({key:E.name,reducer:x}),Object(a.b)({key:E.name,saga:g}),{actions:E.actions}}},136:function(e,t,r){"use strict";r.d(t,"a",(function(){return a})),r.d(t,"b",(function(){return o}));var n=r(170);function a(e){return Object(n.b)(e)}function o(e){return Object(n.c)(e)}},139:function(e,t,r){"use strict";r.d(t,"b",(function(){return O})),r.d(t,"a",(function(){return y}));var n=r(164),a=r(105),o=r(170),c=r(393),i=r(70),s=r(44);function u(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};return 0===Object.keys(e).length?function(e){return e}:Object(s.c)(Object(i.a)({},e))}var l=r(217),d=r(80),f=r(219),p=r.n(f),b=r(218),g={key:"root",storage:p.a,stateReconciler:b.a,whitelist:[]},m=Object(d.g)(g,u()),v=Object(c.a)({}),h=v.run,j=[v],E=[Object(o.a)({createReducer:u,runSaga:h})],O=Object(a.a)({reducer:m,middleware:[].concat(Object(n.a)(Object(a.c)({serializableCheck:{ignoredActions:[d.a,d.f,d.b,d.c,d.d,d.e]}})),j),devTools:!1,enhancers:E});O.subscribe((function(){var e,t=null===(e=O.getState().login)||void 0===e?void 0:e.userInfo.accessToken;t&&l.a.setToken(t)}));var y=Object(d.h)(O)},177:function(e,t,r){"use strict";r.d(t,"a",(function(){return o}));var n=r(64),a=r(132),o=Object(n.a)([function(e){return e.login||a.a}],(function(e){return e}))},209:function(e,t,r){"use strict";r.d(t,"a",(function(){return a}));var n=r(105),a=function(e){return Object(n.b)(e)}},217:function(e,t,r){"use strict";r.d(t,"a",(function(){return n}));var n={token:"",exp:0,logout:[],setToken:function(e){this.token=e},setExpires:function(e){this.exp=e}}},263:function(e,t,r){"use strict";r.d(t,"b",(function(){return a})),r.d(t,"c",(function(){return o})),r.d(t,"a",(function(){return c}));var n=r(45);function a(e){return n.b.post("login",e)}function o(){return n.b.get("logout")}function c(e){return n.b.post("change-password",e)}},268:function(e){e.exports=JSON.parse('{"title":"welcome"}')},272:function(e,t,r){"use strict";r.d(t,"a",(function(){return o}));var n=r(64),a=r(122),o=Object(n.a)([function(e){return e.homePage||a.b}],(function(e){return e}))},273:function(e,t,r){"use strict";r.d(t,"a",(function(){return a})),r.d(t,"b",(function(){return o}));var n=r(174);n.b.config({duration:2,maxCount:3});var a=function(e){n.b.error(e)},o=function(e){n.b.success(e)}},357:function(e,t,r){"use strict";t.a=r.p+"static/media/logo512.dcbc423a.png"},45:function(e,t,r){"use strict";r.d(t,"a",(function(){return s}));var n=r(70),a=r(390),o=r.n(a),c=r(217),i=r(139),s="http://18.216.48.237/api/",u=o.a.create({baseURL:s,headers:{Accept:"application/json","Content-Type":"application/json"}});u.interceptors.request.use((function(e){var t=Object(n.a)({},e),r=c.a.token;return t.headers.common.Authorization=r?"Bearer ".concat(r):"",t})),u.interceptors.response.use((function(e){return e.data.data}),(function(e){throw e&&e.response&&401===e.response.status&&(i.b.dispatch({type:"login/LogoutSuccess"}),i.b.dispatch({type:"homePage/resetValue"})),e})),t.b=u},688:function(e,t,r){"use strict";r.r(t);r(400),r(412);var n,a,o,c,i,s,u=r(0),l=r.n(u),d=r(41),f=r(52),p=(r(602),r(78)),b=r(176),g=r(38),m=r(226),v=r(72),h=Object(v.a)(n||(n=Object(p.a)(["\n  html,\n  body {\n    height: 100%;\n    width: 100%;\n  }\n\n  body {\n    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;\n  }\n\n  #root {\n    min-height: 100%;\n    min-width: 100%;\n  }\n\n  p,\n  label {\n    font-family: Georgia, Times, 'Times New Roman', serif;\n    line-height: 1.5em;\n  }\n\n  input, select {\n    font-family: inherit;\n    font-size: inherit;\n  }\n"]))),j=r(103),E=Object(j.a)((function(){return Promise.all([r.e(0),r.e(5),r.e(6)]).then(r.bind(null,819))}),(function(e){return e.HomePage})),O=Object(j.a)((function(){return Promise.all([r.e(0),r.e(1),r.e(9)]).then(r.bind(null,821))}),(function(e){return e.LoginPage})),y=Object(j.a)((function(){return Promise.all([r.e(0),r.e(1),r.e(7)]).then(r.bind(null,825))}),(function(e){return e.ChangePasswordPage})),x=Object(j.a)((function(){return r.e(8).then(r.bind(null,829))}),(function(e){return e.NotFoundPage})),k=r(695),w=r(693),C=r(273),S=r(357),J=r(692),D=r(690),F=r(694),_=r(696),L=r(697),I=r(177),q=r(132),R=r(272),P=r(122);function Q(){var e,t=Object(g.g)(),r=Object(f.c)(),n=Object(q.c)().actions,a=Object(P.c)().actions.changeTypeAction,o=Object(f.d)(I.a),c=o.userInfo,i=o.errorLogout,s=Object(f.d)(R.a).typeAction,d=function(){r(n.LogoutRequest())};Object(u.useEffect)((function(){i&&Object(C.a)("Something went wrong. Please try again!")}),[i]);var p=function(){r(a("0")),t.push("/change-password")};return l.a.createElement(A,null,l.a.createElement(z,{onClick:function(){t.push("/home")}},l.a.createElement(U,{src:S.a}),l.a.createElement(M,null,"Zim Studio")),l.a.createElement(H,null,1===(null===(e=c.profiles)||void 0===e?void 0:e.role)&&l.a.createElement(D.a,{theme:"dark",mode:"horizontal",selectedKeys:[s],onSelect:function(e){switch(r(a(e.key)),e.key){case"1":t.push("/home");break;case"2":t.push("/home/product")}}},l.a.createElement(D.a.Item,{key:"1"},"Home"),l.a.createElement(D.a.Item,{key:"2"},"Completed Files"))),function(){var e,t;return l.a.createElement(J.a,{placement:"topCenter",overlay:l.a.createElement(l.a.Fragment,null,l.a.createElement(D.a,null,l.a.createElement(D.a.Item,{key:"info",onClick:p},l.a.createElement("span",null,l.a.createElement(_.a,null),"\xa0","Change Password")),l.a.createElement(D.a.Item,{key:"logout",onClick:d},l.a.createElement("span",null,l.a.createElement(L.a,null),"\xa0","Logout"))))},l.a.createElement("span",{className:"ant-dropdown-link",style:{color:"white",cursor:"pointer"}},l.a.createElement(F.a,{style:{backgroundColor:"#0d4b82",marginRight:3}},null===(e=c.profiles)||void 0===e?void 0:e.name[0]),"\xa0",null===(t=c.profiles)||void 0===t?void 0:t.name))}())}var N,T,A=Object(v.c)(w.a.Header)(a||(a=Object(p.a)(["\n  position: fixed;\n  z-index: 1;\n  width: 100%;\n  padding: 0 25px;\n  display: flex;\n  align-items: center;\n"]))),z=v.c.a(o||(o=Object(p.a)(["\n  display: flex;\n  flex-direction: row;\n  height: 100%;\n  align-items: center;\n"]))),U=v.c.img(c||(c=Object(p.a)(["\n  width: 40px;\n  height: 40px;\n  object-fit: contain;\n"]))),M=v.c.div(i||(i=Object(p.a)(["\n  color: #fff;\n  padding-left: 10px;\n  font-size: large;\n  font-weight: bold;\n"]))),H=v.c.div(s||(s=Object(p.a)(["\n  flex: 1;\n  margin-left: 30px;\n"]))),V=function(e){var t=e.type;return"guest"===t&&localStorage.getItem("user")?l.a.createElement(g.a,{to:"/home"}):"private"!==t||localStorage.getItem("user")?l.a.createElement(g.b,e):l.a.createElement(g.a,{to:"/"})};function Z(){var e=Object(k.a)().i18n,t=Object(q.c)().actions,r=Object(f.c)(),n=Object(f.d)(I.a).userInfo;return Object(u.useEffect)((function(){var e,n=(e="user",localStorage.getItem(e)||"{}");localStorage.getItem("user")&&r(t.LoginSuccess(JSON.parse(n)))}),[t,r]),l.a.createElement(m.a,{hashType:"noslash"},l.a.createElement(b.a,{titleTemplate:"%s - ZimFiles",defaultTitle:"ZimFiles",htmlAttributes:{lang:e.language}},l.a.createElement("meta",{name:"description",content:"A ZimFiles application"})),l.a.createElement(B,null,n.accessToken&&l.a.createElement(Q,null),l.a.createElement(G,{ischeck:n.accessToken?"1":"2"},l.a.createElement(g.d,null,l.a.createElement(V,{type:"guest",exact:!0,path:"/",component:O}),l.a.createElement(V,{exact:!0,type:"private",path:"/home"},l.a.createElement(E,null)),l.a.createElement(V,{exact:!0,type:"private",path:"/home/:folderId"},l.a.createElement(E,null)),l.a.createElement(V,{exact:!0,type:"private",path:"/home/image/:ImageFolderId"},l.a.createElement(E,null)),l.a.createElement(V,{exact:!0,type:"private",path:"/change-password"},l.a.createElement(y,null)),l.a.createElement(g.b,{component:x})))),l.a.createElement(h,null))}var B=Object(v.c)(w.a)(N||(N=Object(p.a)(["\n  min-height: 100vh;\n  display: flex;\n"]))),G=Object(v.c)(w.a.Content)(T||(T=Object(p.a)(["\n  display: flex;\n  flex: 1;\n  flex-direction: column;\n  margin-top: ","px;\n  background-color: #dddddd;\n"])),(function(e){return"1"===e.ischeck?64:0})),K=r(139),Y=function(e){e&&e instanceof Function&&r.e(10).then(r.bind(null,816)).then((function(t){var r=t.getCLS,n=t.getFID,a=t.getFCP,o=t.getLCP,c=t.getTTFB;r(e),n(e),a(e),o(e),c(e)}))},W=r(391),X=r(396),$=r(173),ee=r(392),te=r(268),re={},ne={en:{translation:te}};!function e(t){var r=arguments.length>1&&void 0!==arguments[1]?arguments[1]:re,n=arguments.length>2?arguments[2]:void 0;Object.keys(t).forEach((function(a){var o=n?"".concat(n,".").concat(a):a;"object"===typeof t[a]?(r[a]={},e(t[a],r[a],o)):r[a]=o}))}(te);X.a.use($.e).use(ee.a).init({resources:ne,fallbackLng:"en",debug:!1,interpolation:{escapeValue:!1}});var ae=document.getElementById("root");d.render(u.createElement(f.a,{store:K.b},u.createElement(W.a,{loading:null,persistor:K.a},u.createElement(b.b,null,u.createElement(u.StrictMode,null,u.createElement(Z,null))))),ae),Y()}},[[688,3,4]]]);