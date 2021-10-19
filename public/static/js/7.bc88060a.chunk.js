(this.webpackJsonpzimfiles=this.webpackJsonpzimfiles||[]).push([[7],{626:function(e,a,n){"use strict";n.d(a,"a",(function(){return t}));var r=n(252);function t(e,a){return function(e){if(Array.isArray(e))return e}(e)||function(e,a){if("undefined"!==typeof Symbol&&Symbol.iterator in Object(e)){var n=[],r=!0,t=!1,s=void 0;try{for(var o,c=e[Symbol.iterator]();!(r=(o=c.next()).done)&&(n.push(o.value),!a||n.length!==a);r=!0);}catch(i){t=!0,s=i}finally{try{r||null==c.return||c.return()}finally{if(t)throw s}}return n}}(e,a)||Object(r.a)(e,a)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}},707:function(e,a,n){"use strict";n.r(a),n.d(a,"ChangePasswordPage",(function(){return V}));var r=n(626),t=n(0),s=n.n(t),o=n(162),c=n(41),i=n(55),l=n(184),u=n(124),d=n(26),m=n.n(d),p=n(10),f=n(233),w=m.a.mark(b),h=m.a.mark(g);function b(e){var a,n,r,t,s;return m.a.wrap((function(o){for(;;)switch(o.prev=o.next){case 0:return o.prev=0,a=e.payload,n=a.oldPass,r=a.newPass,t={old_password:n,new_password:r},o.next=5,Object(p.a)(f.a,t);case 5:return s=o.sent,o.next=8,Object(p.b)(E.ChangePasswordSuccess(s));case 8:o.next=14;break;case 10:return o.prev=10,o.t0=o.catch(0),o.next=14,Object(p.b)(E.ChangePasswordError(o.t0.response.status));case 14:case"end":return o.stop()}}),w,null,[[0,10]])}function g(){return m.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,Object(p.d)(E.ChangePasswordRequest,b);case 2:case"end":return e.stop()}}),h)}var P,y,j,v={loading:!1,error:0,data:void 0,checkDone:!1},O=Object(l.a)({name:"changepassword",initialState:v,reducers:{ChangePasswordRequest:function(e,a){e.loading=!0,e.error=0},ChangePasswordSuccess:function(e,a){e.loading=!1,e.data=a.payload,e.checkDone=!0},ChangePasswordError:function(e,a){e.loading=!1,e.error=a.payload},ChangePasswordDone:function(e,a){e.checkDone=!1}}}),E=O.actions,x=Object(i.a)([function(e){return e.changepassword||v}],(function(e){return e})),C=n(244),k=n(34),S=n(699),F=n(701),q=n(69),D=n(65),T=n(165),z=D.c.div(P||(P=Object(q.a)(["\n  display: flex;\n  flex: 0.5;\n  justify-content: center;\n  align-items: center;\n"]))),I=D.c.div(y||(y=Object(q.a)(["\n  display: flex;\n  flex: 1;\n  justify-content: center;\n  align-items: center;\n  margin-top: 30px;\n"]))),A=Object(D.c)(T.a)(j||(j=Object(q.a)(["\n  border-radius: 20px;\n  width: 120px;\n"]))),R={labelCol:{xs:{span:24},sm:{span:10}},wrapperCol:{xs:{span:24},sm:{span:16}}};function V(){var e=Object(c.c)(),a=(Object(u.a)({key:O.name,reducer:O.reducer}),Object(u.b)({key:O.name,saga:g}),{actions:O.actions}).actions,n=Object(c.d)(x),i=n.loading,l=n.error,d=n.checkDone,m=S.a.useForm(),p=Object(r.a)(m,1)[0],f=Object(k.g)();Object(t.useEffect)((function(){422===l?Object(C.a)("The old password is not correct! Please try again!"):l&&Object(C.a)("Somthing went wrong! Please try again!")}),[l]),Object(t.useLayoutEffect)((function(){d&&(Object(C.b)("Password change successful!"),f.push("/home"),e(a.ChangePasswordDone()))}),[a,d,e,f]);return s.a.createElement(s.a.Fragment,null,s.a.createElement(o.a,null,s.a.createElement("title",null,"Home Page"),s.a.createElement("meta",{name:"description",content:"A ZimFiles application change password page"})),s.a.createElement(z,null,s.a.createElement(S.a,Object.assign({},R,{form:p,name:"register",onFinish:function(n){var r=n.old,t=n.password;e(a.ChangePasswordRequest({oldPass:r,newPass:t}))},style:{minWidth:"40%"},initialValues:{residence:["zhejiang","hangzhou","xihu"],prefix:"86"},scrollToFirstError:!0}),s.a.createElement(S.a.Item,{name:"old",label:"Old Password",rules:[{required:!0,min:6,max:30,message:"Your Old Password is not valid!"}]},s.a.createElement(F.a.Password,null)),s.a.createElement(S.a.Item,{name:"password",label:"Password",dependencies:["old"],rules:[{required:!0,min:6,max:30,message:"Your New Password is not valid!"},function(e){var a=e.getFieldValue;return{validator:function(e,n){return n&&a("old")===n?Promise.reject(new Error("The new password and the old password are the same!")):Promise.resolve()}}}]},s.a.createElement(F.a.Password,null)),s.a.createElement(S.a.Item,{name:"confirm",label:"Confirm Password",dependencies:["password"],rules:[{required:!0,message:"Please confirm your password!"},function(e){var a=e.getFieldValue;return{validator:function(e,n){return n&&a("password")!==n?Promise.reject(new Error("The two passwords that you entered do not match!")):Promise.resolve()}}}]},s.a.createElement(F.a.Password,null)),s.a.createElement(I,null,s.a.createElement(A,{loading:i,type:"primary",htmlType:"submit",size:"large"},"Submit")))))}}}]);