(this.webpackJsonpzimfiles = this.webpackJsonpzimfiles || []).push([[10], {
    698: function (e, t, n) {
        "use strict";
        n.r(t), n.d(t, "LoginPage", (function () {
            return me
        }));
        var a, r, i, c, l, o, s, m, p, d, u, f, g, b = n(0), x = n.n(b), h = n(156), v = n(65), j = n(61),
            O = {small: 600, medium: 1024, large: 1440, xlarge: 1920}, y = Object.keys(O).reduce((function (e, t) {
                return e[t] = function (e) {
                    for (var n = arguments.length, a = new Array(n > 1 ? n - 1 : 0), r = 1; r < n; r++) a[r - 1] = arguments[r];
                    return Object(j.b)(["@media (min-width:", "px){", "}"], O[t], j.b.apply(void 0, [e].concat(a)))
                }, e
            }), {}), w = n.p + "static/media/background.c10f5053.jpg", E = n(697), z = n(160),
            k = j.c.div(a || (a = Object(v.a)(["\n  display: flex;\n  flex: 1;\n  flex-direction: row;\n  background-color: white;\n  flex-wrap: wrap;\n  justify-content: center;\n  align-items: center;\n  background-image: url(", ");\n  background-attachment: fixed;\n  background-size: cover;\n"])), w),
            L = j.c.div(r || (r = Object(v.a)(["\n  display: flex;\n  width: 490px;\n  height: 380px;\n  border-radius: 10px;\n  background-color: #fff;\n  box-shadow: 0 0 30px #c5c1c1;\n  ", ";\n"])), y.medium(i || (i = Object(v.a)(["\n  width: 900px;\n  height: 600px;\n  "])))),
            P = j.c.div(c || (c = Object(v.a)(["\n  ", ";\n  display: none;\n"])), y.medium(l || (l = Object(v.a)(["\n  flex: 1;\n  display: flex;\n  flex-direction: column;\n  "])))),
            I = j.c.img(o || (o = Object(v.a)(["\n  height: 80px;\n  width: 80px;\n  object-fit: contain;\n  margin: 5px 0 20px 5px;\n"]))),
            V = j.c.img(s || (s = Object(v.a)(["\n  height: 400px;\n  object-fit: contain;\n"]))),
            M = j.c.div(m || (m = Object(v.a)(["\n  ", ";\n  flex: 1;\n  align-items: center;\n  display: flex;\n  justify-content: center;\n"])), y.medium(p || (p = Object(v.a)(["\n  flex: 1;\n  align-items: center;\n  display: flex;\n  justify-content: flex-start;\n  "])))),
            S = j.c.div(d || (d = Object(v.a)(["\n  margin-bottom: 20px;\n  text-align: center;\n  font-size: 49px;\n  color: #0d4b82;\n  font-weight: bold;\n  text-shadow: 1px 2px 3px #6479c5;\n  font-family: initial;\n"]))),
            N = Object(j.c)(E.a)(u || (u = Object(v.a)(["\n  border-radius: 5px;\n"]))),
            A = Object(j.c)(E.a.Password)(f || (f = Object(v.a)(["\n  border-radius: 5px;\n"]))),
            C = Object(j.c)(z.a)(g || (g = Object(v.a)(["\n  border-radius: 20px;\n  width: 120px;\n"]))), F = n(321),
            H = n.p + "static/media/AppDevelopmentIllustration.0e5c56c2.jpg", T = n(695), B = n(1), G = n(4), R = n(7),
            q = n(5), D = n.n(q), J = n(73), W = n(94);

        function Y(e) {
            var t = e.className, n = e.direction, a = e.index, r = e.marginDirection, i = e.children, c = e.split,
                l = e.wrap, o = b.useContext(K), s = o.horizontalSize, m = o.verticalSize, p = o.latestIndex, d = {};
            return o.supportFlexGap || ("vertical" === n ? a < p && (d = {marginBottom: s / (c ? 2 : 1)}) : d = Object(B.a)(Object(B.a)({}, a < p && Object(G.a)({}, r, s / (c ? 2 : 1))), l && {paddingBottom: m})), null === i || void 0 === i ? null : b.createElement(b.Fragment, null, b.createElement("div", {
                className: t,
                style: d
            }, i), a < p && c && b.createElement("span", {className: "".concat(t, "-split"), style: d}, c))
        }

        var Z = n(638), _ = function (e, t) {
                var n = {};
                for (var a in e) Object.prototype.hasOwnProperty.call(e, a) && t.indexOf(a) < 0 && (n[a] = e[a]);
                if (null != e && "function" === typeof Object.getOwnPropertySymbols) {
                    var r = 0;
                    for (a = Object.getOwnPropertySymbols(e); r < a.length; r++) t.indexOf(a[r]) < 0 && Object.prototype.propertyIsEnumerable.call(e, a[r]) && (n[a[r]] = e[a[r]])
                }
                return n
            }, K = b.createContext({latestIndex: 0, horizontalSize: 0, verticalSize: 0, supportFlexGap: !1}),
            Q = {small: 8, middle: 16, large: 24};
        var U = function (e) {
            var t, n = b.useContext(W.b), a = n.getPrefixCls, r = n.space, i = n.direction, c = e.size,
                l = void 0 === c ? (null === r || void 0 === r ? void 0 : r.size) || "small" : c, o = e.align,
                s = e.className, m = e.children, p = e.direction, d = void 0 === p ? "horizontal" : p, u = e.prefixCls,
                f = e.split, g = e.style, x = e.wrap, h = void 0 !== x && x,
                v = _(e, ["size", "align", "className", "children", "direction", "prefixCls", "split", "style", "wrap"]),
                j = Object(Z.a)(), O = b.useMemo((function () {
                    return (Array.isArray(l) ? l : [l, l]).map((function (e) {
                        return function (e) {
                            return "string" === typeof e ? Q[e] : e || 0
                        }(e)
                    }))
                }), [l]), y = Object(R.a)(O, 2), w = y[0], E = y[1], z = Object(J.a)(m, {keepEmpty: !0}),
                k = void 0 === o && "horizontal" === d ? "center" : o, L = a("space", u),
                P = D()(L, "".concat(L, "-").concat(d), (t = {}, Object(G.a)(t, "".concat(L, "-rtl"), "rtl" === i), Object(G.a)(t, "".concat(L, "-align-").concat(k), k), t), s),
                I = "".concat(L, "-item"), V = "rtl" === i ? "marginLeft" : "marginRight", M = 0,
                S = z.map((function (e, t) {
                    return null !== e && void 0 !== e && (M = t), b.createElement(Y, {
                        className: I,
                        key: "".concat(I, "-").concat(t),
                        direction: d,
                        index: t,
                        marginDirection: V,
                        split: f,
                        wrap: h
                    }, e)
                })), N = b.useMemo((function () {
                    return {horizontalSize: w, verticalSize: E, latestIndex: M, supportFlexGap: j}
                }), [w, E, M, j]);
            if (0 === z.length) return null;
            var A = {};
            return h && (A.flexWrap = "wrap", j || (A.marginBottom = -E)), j && (A.columnGap = w, A.rowGap = E), b.createElement("div", Object(B.a)({
                className: P,
                style: Object(B.a)(Object(B.a)({}, A), g)
            }, v), b.createElement(K.Provider, {value: N}, S))
        }, X = {
            icon: function (e, t) {
                return {
                    tag: "svg",
                    attrs: {viewBox: "64 64 896 896", focusable: "false"},
                    children: [{
                        tag: "path",
                        attrs: {
                            d: "M477.5 536.3L135.9 270.7l-27.5-21.4 27.6 21.5V792h752V270.8L546.2 536.3a55.99 55.99 0 01-68.7 0z",
                            fill: t
                        }
                    }, {
                        tag: "path",
                        attrs: {d: "M876.3 198.8l39.3 50.5-27.6 21.5 27.7-21.5-39.3-50.5z", fill: t}
                    }, {
                        tag: "path",
                        attrs: {
                            d: "M928 160H96c-17.7 0-32 14.3-32 32v640c0 17.7 14.3 32 32 32h832c17.7 0 32-14.3 32-32V192c0-17.7-14.3-32-32-32zm-94.5 72.1L512 482 190.5 232.1h643zm54.5 38.7V792H136V270.8l-27.6-21.5 27.5 21.4 341.6 265.6a55.99 55.99 0 0068.7 0L888 270.8l27.6-21.5-39.3-50.5h.1l39.3 50.5-27.7 21.5z",
                            fill: e
                        }
                    }]
                }
            }, name: "mail", theme: "twotone"
        }, $ = n(18), ee = function (e, t) {
            return b.createElement($.a, Object.assign({}, e, {ref: t, icon: X}))
        };
        ee.displayName = "MailTwoTone";
        var te = b.forwardRef(ee), ne = {
            icon: function (e, t) {
                return {
                    tag: "svg",
                    attrs: {viewBox: "64 64 896 896", focusable: "false"},
                    children: [{
                        tag: "path",
                        attrs: {
                            d: "M832 464h-68V240c0-70.7-57.3-128-128-128H388c-70.7 0-128 57.3-128 128v224h-68c-17.7 0-32 14.3-32 32v384c0 17.7 14.3 32 32 32h640c17.7 0 32-14.3 32-32V496c0-17.7-14.3-32-32-32zM332 240c0-30.9 25.1-56 56-56h248c30.9 0 56 25.1 56 56v224H332V240zm460 600H232V536h560v304z",
                            fill: e
                        }
                    }, {
                        tag: "path",
                        attrs: {
                            d: "M232 840h560V536H232v304zm280-226a48.01 48.01 0 0128 87v53c0 4.4-3.6 8-8 8h-40c-4.4 0-8-3.6-8-8v-53a48.01 48.01 0 0128-87z",
                            fill: t
                        }
                    }, {
                        tag: "path",
                        attrs: {d: "M484 701v53c0 4.4 3.6 8 8 8h40c4.4 0 8-3.6 8-8v-53a48.01 48.01 0 10-56 0z", fill: e}
                    }]
                }
            }, name: "lock", theme: "twotone"
        }, ae = function (e, t) {
            return b.createElement($.a, Object.assign({}, e, {ref: t, icon: ne}))
        };
        ae.displayName = "LockTwoTone";
        var re = b.forwardRef(ae), ie = n(46), ce = n(143), le = n(182), oe = n(32), se = n(240);

        function me(e) {
            var t = e.location, n = Object(le.c)().actions, a = Object(ie.d)(ce.a), r = a.loadingLogin, i = a.error,
                c = a.userInfo, l = Object(ie.c)(), o = Object(oe.g)(),
                s = (t.state || {from: {pathname: "/home"}}).from;
            Object(b.useEffect)((function () {
                401 === i ? Object(se.a)("Invalid email or password. Please try again!") : i && Object(se.a)("Someting went wrong. Please try again!")
            }), [i]), Object(b.useLayoutEffect)((function () {
                c.accessToken && o.replace(s)
            }), [o, c, s]);
            return x.a.createElement(k, null, x.a.createElement(h.a, null, x.a.createElement("title", null, "Login Page"), x.a.createElement("meta", {
                name: "description",
                content: "A ZimFiles application login page"
            })), x.a.createElement(L, null, x.a.createElement(P, null, x.a.createElement(I, {src: F.a}), x.a.createElement(V, {src: H})), x.a.createElement(M, null, x.a.createElement(T.a, {
                name: "login_form",
                onFinish: function (e) {
                    l(n.LoginRequest({email: e.email, password: e.password, remember: e.remember}))
                },
                style: {minWidth: "320px"},
                initialValues: {remember: !0}
            }, x.a.createElement(S, null, "Login"), x.a.createElement(T.a.Item, {
                name: "email",
                rules: [{required: !0, max: 50, type: "email", message: "Your Email is not valid!"}]
            }, x.a.createElement(N, {
                prefix: x.a.createElement(te, null),
                placeholder: "Email",
                size: "large"
            })), x.a.createElement(T.a.Item, {
                name: "password",
                rules: [{required: !0, min: 6, max: 30, message: "Your Password is not valid!"}]
            }, x.a.createElement(A, {
                prefix: x.a.createElement(re, null),
                type: "password",
                placeholder: "Password",
                size: "large"
            })), x.a.createElement(T.a.Item, {style: {textAlign: "center"}}, x.a.createElement(U, null, x.a.createElement(C, {
                loading: r,
                type: "primary",
                htmlType: "submit",
                size: "large"
            }, "Sign In")))))))
        }
    }
}]);
