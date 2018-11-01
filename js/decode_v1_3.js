(function(e, r) {
    if ("object" == typeof exports) {
        module.exports = r();
    } else {
        if ("function" == typeof define && define.amd) {
            define(r);
        } else {
            e.GibberishAES = r();
        }
    }
})(this, function() {
    var e$$1 = 14;
    var r$$2 = 8;
    var n$$1 = false;
    var f = function(e) {
        try {
            return unescape(encodeURIComponent(e));
        } catch (r) {
            throw "Error on UTF-8 encode";
        }
    };
    var c = function(e) {
        try {
            return decodeURIComponent(escape(e));
        } catch (r) {
            throw "Bad Key";
        }
    };
    var t = function(e) {
        var r;
        var n;
        var f = [];
        if (16 > e.length) {
            r = 16 - e.length;
            f = [r, r, r, r, r, r, r, r, r, r, r, r, r, r, r, r];
        }
        n = 0;
        for (; e.length > n; n++) {
            f[n] = e[n];
        }
        return f;
    };
    var a = function(e, r) {
        var n;
        var f;
        var c = "";
        if (r) {
            if (n = e[15], n > 16) {
                throw "Decryption error: Maybe bad key";
            }
            if (16 === n) {
                return "";
            }
            f = 0;
            for (; 16 - n > f; f++) {
                c += String.fromCharCode(e[f]);
            }
        } else {
            f = 0;
            for (; 16 > f; f++) {
                c += String.fromCharCode(e[f]);
            }
        }
        return c;
    };
    var o = function(e) {
        var r;
        var n = "";
        r = 0;
        for (; e.length > r; r++) {
            n += (16 > e[r] ? "0" : "") + e[r].toString(16);
        }
        return n;
    };
    var d = function(e$$0) {
        var r = [];
        return e$$0.replace(/(..)/g, function(e) {
            r.push(parseInt(e, 16));
        }), r;
    };
    var u = function(e, r) {
        var n;
        var c = [];
        if (!r) {
            e = f(e);
        }
        n = 0;
        for (; e.length > n; n++) {
            c[n] = e.charCodeAt(n);
        }
        return c;
    };
    var i$$0 = function(n) {
        switch (n) {
            case 128:
                e$$1 = 10;
                r$$2 = 4;
                break;
            case 192:
                e$$1 = 12;
                r$$2 = 6;
                break;
            case 256:
                e$$1 = 14;
                r$$2 = 8;
                break;
            default:
                throw "Invalid Key Size Specified:" + n;;
        }
    };
    var b = function(e) {
        var r;
        var n = [];
        r = 0;
        for (; e > r; r++) {
            n = n.concat(Math.floor(256 * Math.random()));
        }
        return n;
    };
    var h$$0 = function(n, f) {
        var c;
        var t = e$$1 >= 12 ? 3 : 2;
        var a = [];
        var o = [];
        var d = [];
        var u = [];
        var i = n.concat(f);
        d[0] = L(i);
        u = d[0];
        c = 1;
        for (; t > c; c++) {
            d[c] = L(d[c - 1].concat(i));
            u = u.concat(d[c]);
        }
        return a = u.slice(0, 4 * r$$2), o = u.slice(4 * r$$2, 4 * r$$2 + 16), {
            key: a,
            iv: o
        };
    };
    var l = function(e, r, n) {
        r = S(r);
        var f;
        var c = Math.ceil(e.length / 16);
        var a = [];
        var o = [];
        f = 0;
        for (; c > f; f++) {
            a[f] = t(e.slice(16 * f, 16 * f + 16));
        }
        if (0 === e.length % 16) {
            a.push([16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16, 16]);
            c++;
        }
        f = 0;
        for (; a.length > f; f++) {
            a[f] = 0 === f ? x$$0(a[f], n) : x$$0(a[f], o[f - 1]);
            o[f] = s$$0(a[f], r);
        }
        return o;
    };
    var v = function(e, r, n, f) {
        r = S(r);
        var t;
        var o = e.length / 16;
        var d = [];
        var u = [];
        var i = "";
        t = 0;
        for (; o > t; t++) {
            d.push(e.slice(16 * t, 16 * (t + 1)));
        }
        t = d.length - 1;
        for (; t >= 0; t--) {
            u[t] = p(d[t], r);
            u[t] = 0 === t ? x$$0(u[t], n) : x$$0(u[t], d[t - 1]);
        }
        t = 0;
        for (; o - 1 > t; t++) {
            i += a(u[t]);
        }
        return i += a(u[t], true), f ? i : c(i);
    };
    var s$$0 = function(r, f) {
        n$$1 = false;
        var c;
        var t = M(r, f, 0);
        c = 1;
        for (; e$$1 + 1 > c; c++) {
            t = g(t);
            t = y$$0(t);
            if (e$$1 > c) {
                t = k(t);
            }
            t = M(t, f, c);
        }
        return t;
    };
    var p = function(r, f) {
        n$$1 = true;
        var c;
        var t = M(r, f, e$$1);
        c = e$$1 - 1;
        for (; c > -1; c--) {
            t = y$$0(t);
            t = g(t);
            t = M(t, f, c);
            if (c > 0) {
                t = k(t);
            }
        }
        return t;
    };
    var g = function(e) {
        var r;
        var f = n$$1 ? D : B;
        var c = [];
        r = 0;
        for (; 16 > r; r++) {
            c[r] = f[e[r]];
        }
        return c;
    };
    var y$$0 = function(e) {
        var r;
        var f = [];
        var c = n$$1 ? [0, 13, 10, 7, 4, 1, 14, 11, 8, 5, 2, 15, 12, 9, 6, 3] : [0, 5, 10, 15, 4, 9, 14, 3, 8, 13, 2, 7, 12, 1, 6, 11];
        r = 0;
        for (; 16 > r; r++) {
            f[r] = e[c[r]];
        }
        return f;
    };
    var k = function(e) {
        var r;
        var f = [];
        if (n$$1) {
            r = 0;
            for (; 4 > r; r++) {
                f[4 * r] = F[e[4 * r]] ^ R[e[1 + 4 * r]] ^ j[e[2 + 4 * r]] ^ z[e[3 + 4 * r]];
                f[1 + 4 * r] = z[e[4 * r]] ^ F[e[1 + 4 * r]] ^ R[e[2 + 4 * r]] ^ j[e[3 + 4 * r]];
                f[2 + 4 * r] = j[e[4 * r]] ^ z[e[1 + 4 * r]] ^ F[e[2 + 4 * r]] ^ R[e[3 + 4 * r]];
                f[3 + 4 * r] = R[e[4 * r]] ^ j[e[1 + 4 * r]] ^ z[e[2 + 4 * r]] ^ F[e[3 + 4 * r]];
            }
        } else {
            r = 0;
            for (; 4 > r; r++) {
                f[4 * r] = E[e[4 * r]] ^ U[e[1 + 4 * r]] ^ e[2 + 4 * r] ^ e[3 + 4 * r];
                f[1 + 4 * r] = e[4 * r] ^ E[e[1 + 4 * r]] ^ U[e[2 + 4 * r]] ^ e[3 + 4 * r];
                f[2 + 4 * r] = e[4 * r] ^ e[1 + 4 * r] ^ E[e[2 + 4 * r]] ^ U[e[3 + 4 * r]];
                f[3 + 4 * r] = U[e[4 * r]] ^ e[1 + 4 * r] ^ e[2 + 4 * r] ^ E[e[3 + 4 * r]];
            }
        }
        return f;
    };
    var M = function(e, r, n) {
        var f;
        var c = [];
        f = 0;
        for (; 16 > f; f++) {
            c[f] = e[f] ^ r[n][f];
        }
        return c;
    };
    var x$$0 = function(e, r) {
        var n;
        var f = [];
        n = 0;
        for (; 16 > n; n++) {
            f[n] = e[n] ^ r[n];
        }
        return f;
    };
    var S = function(n) {
        var f;
        var c;
        var t;
        var a;
        var o = [];
        var d = [];
        var u = [];
        f = 0;
        for (; r$$2 > f; f++) {
            c = [n[4 * f], n[4 * f + 1], n[4 * f + 2], n[4 * f + 3]];
            o[f] = c;
        }
        f = r$$2;
        for (; 4 * (e$$1 + 1) > f; f++) {
            o[f] = [];
            t = 0;
            for (; 4 > t; t++) {
                d[t] = o[f - 1][t];
            }
            if (0 === f % r$$2) {
                d = m(w(d));
                d[0] ^= K[f / r$$2 - 1];
            } else {
                if (r$$2 > 6) {
                    if (4 === f % r$$2) {
                        d = m(d);
                    }
                }
            }
            t = 0;
            for (; 4 > t; t++) {
                o[f][t] = o[f - r$$2][t] ^ d[t];
            }
        }
        f = 0;
        for (; e$$1 + 1 > f; f++) {
            u[f] = [];
            a = 0;
            for (; 4 > a; a++) {
                u[f].push(o[4 * f + a][0], o[4 * f + a][1], o[4 * f + a][2], o[4 * f + a][3]);
            }
        }
        return u;
    };
    var m = function(e) {
        var r = 0;
        for (; 4 > r; r++) {
            e[r] = B[e[r]];
        }
        return e;
    };
    var w = function(e) {
        var r;
        var n = e[0];
        r = 0;
        for (; 4 > r; r++) {
            e[r] = e[r + 1];
        }
        return e[3] = n, e;
    };
    var A = function(e, r) {
        var n;
        var f = [];
        n = 0;
        for (; e.length > n; n += r) {
            f[n / r] = parseInt(e.substr(n, r), 16);
        }
        return f;
    };
    var C = function(e) {
        var r;
        var n = [];
        r = 0;
        for (; e.length > r; r++) {
            n[e[r]] = r;
        }
        return n;
    };
    var I = function(e, r) {
        var n;
        var f;
        f = 0;
        n = 0;
        for (; 8 > n; n++) {
            f = 1 === (1 & r) ? f ^ e : f;
            e = e > 127 ? 283 ^ e << 1 : e << 1;
            r >>>= 1;
        }
        return f;
    };
    var O = function(e) {
        var r;
        var n = [];
        r = 0;
        for (; 256 > r; r++) {
            n[r] = I(e, r);
        }
        return n;
    };
    var B = A("637c777bf26b6fc53001672bfed7ab76ca82c97dfa5947f0add4a2af9ca472c0b7fd9326363ff7cc34a5e5f171d8311504c723c31896059a071280e2eb27b27509832c1a1b6e5aa0523bd6b329e32f8453d100ed20fcb15b6acbbe394a4c58cfd0efaafb434d338545f9027f503c9fa851a3408f929d38f5bcb6da2110fff3d2cd0c13ec5f974417c4a77e3d645d197360814fdc222a908846eeb814de5e0bdbe0323a0a4906245cc2d3ac629195e479e7c8376d8dd54ea96c56f4ea657aae08ba78252e1ca6b4c6e8dd741f4bbd8b8a703eb5664803f60e613557b986c11d9ee1f8981169d98e949b1e87e9ce5528df8ca1890dbfe6426841992d0fb054bb16",
        2);
    var D = C(B);
    var K = A("01020408102040801b366cd8ab4d9a2f5ebc63c697356ad4b37dfaefc591", 2);
    var E = O(2);
    var U = O(3);
    var z = O(9);
    var R = O(11);
    var j = O(13);
    var F = O(14);
    var G = function(e, r, n) {
        var f;
        var c = b(8);
        var t = h$$0(u(r, n), c);
        var a = t.key;
        var o = t.iv;
        var d = [
            [83, 97, 108, 116, 101, 100, 95, 95].concat(c)
        ];
        return e = u(e, n), f = l(e, a, o), f = d.concat(f), T.encode(f);
    };
    var H = function(e, r, n) {
        var f = T.decode(e);
        var c = f.slice(8, 16);
        var t = h$$0(u(r, n), c);
        var a = t.key;
        var o = t.iv;
        return f = f.slice(16, f.length), e = v(f, a, o, n);
    };
    var L = function(e$$0) {
        function r$$0(e, r) {
            return e << r | e >>> 32 - r;
        }

        function n$$0(e, r) {
            var n;
            var f;
            var c;
            var t;
            var a;
            return c = 2147483648 & e, t = 2147483648 & r, n = 1073741824 & e, f = 1073741824 & r, a = (1073741823 & e) + (1073741823 & r), n & f ? 2147483648 ^ a ^ c ^ t : n | f ? 1073741824 & a ? 3221225472 ^ a ^ c ^ t : 1073741824 ^ a ^ c ^ t : a ^ c ^ t;
        }

        function f$$0(e, r, n) {
            return e & r | ~e & n;
        }

        function c$$0(e, r, n) {
            return e & n | r & ~n;
        }

        function t$$0(e, r, n) {
            return e ^ r ^ n;
        }

        function a$$0(e, r, n) {
            return r ^ (e | ~n);
        }

        function o$$0(e, c, t, a, o, d, u) {
            return e = n$$0(e, n$$0(n$$0(f$$0(c, t, a), o), u)), n$$0(r$$0(e, d), c);
        }

        function d$$0(e, f, t, a, o, d, u) {
            return e = n$$0(e, n$$0(n$$0(c$$0(f, t, a), o), u)), n$$0(r$$0(e, d), f);
        }

        function u$$0(e, f, c, a, o, d, u) {
            return e = n$$0(e, n$$0(n$$0(t$$0(f, c, a), o), u)), n$$0(r$$0(e, d), f);
        }

        function i(e, f, c, t, o, d, u) {
            return e = n$$0(e, n$$0(n$$0(a$$0(f, c, t), o), u)), n$$0(r$$0(e, d), f);
        }

        function b(e) {
            var r;
            var n = e.length;
            var f = n + 8;
            var c = (f - f % 64) / 64;
            var t = 16 * (c + 1);
            var a = [];
            var o = 0;
            var d = 0;
            for (; n > d;) {
                r = (d - d % 4) / 4;
                o = 8 * (d % 4);
                a[r] = a[r] | e[d] << o;
                d++;
            }
            return r = (d - d % 4) / 4, o = 8 * (d % 4), a[r] = a[r] | 128 << o, a[t - 2] = n << 3, a[t - 1] = n >>> 29, a;
        }

        function h(e) {
            var r;
            var n;
            var f = [];
            n = 0;
            for (; 3 >= n; n++) {
                r = 255 & e >>> 8 * n;
                f = f.concat(r);
            }
            return f;
        }
        var l;
        var v;
        var s;
        var p;
        var g;
        var y;
        var k;
        var M;
        var x;
        var S = [];
        var m = A("67452301efcdab8998badcfe10325476d76aa478e8c7b756242070dbc1bdceeef57c0faf4787c62aa8304613fd469501698098d88b44f7afffff5bb1895cd7be6b901122fd987193a679438e49b40821f61e2562c040b340265e5a51e9b6c7aad62f105d02441453d8a1e681e7d3fbc821e1cde6c33707d6f4d50d87455a14eda9e3e905fcefa3f8676f02d98d2a4c8afffa39428771f6816d9d6122fde5380ca4beea444bdecfa9f6bb4b60bebfbc70289b7ec6eaa127fad4ef308504881d05d9d4d039e6db99e51fa27cf8c4ac5665f4292244432aff97ab9423a7fc93a039655b59c38f0ccc92ffeff47d85845dd16fa87e4ffe2ce6e0a30143144e0811a1f7537e82bd3af2352ad7d2bbeb86d391",
            8);
        S = b(e$$0);
        y = m[0];
        k = m[1];
        M = m[2];
        x = m[3];
        l = 0;
        for (; S.length > l; l += 16) {
            v = y;
            s = k;
            p = M;
            g = x;
            y = o$$0(y, k, M, x, S[l + 0], 7, m[4]);
            x = o$$0(x, y, k, M, S[l + 1], 12, m[5]);
            M = o$$0(M, x, y, k, S[l + 2], 17, m[6]);
            k = o$$0(k, M, x, y, S[l + 3], 22, m[7]);
            y = o$$0(y, k, M, x, S[l + 4], 7, m[8]);
            x = o$$0(x, y, k, M, S[l + 5], 12, m[9]);
            M = o$$0(M, x, y, k, S[l + 6], 17, m[10]);
            k = o$$0(k, M, x, y, S[l + 7], 22, m[11]);
            y = o$$0(y, k, M, x, S[l + 8], 7, m[12]);
            x = o$$0(x, y, k, M, S[l + 9], 12, m[13]);
            M = o$$0(M, x, y, k, S[l + 10], 17, m[14]);
            k = o$$0(k, M, x, y, S[l + 11], 22, m[15]);
            y = o$$0(y, k, M, x, S[l + 12], 7, m[16]);
            x = o$$0(x, y, k, M, S[l + 13], 12, m[17]);
            M = o$$0(M, x, y, k, S[l + 14], 17, m[18]);
            k = o$$0(k, M, x, y, S[l + 15], 22, m[19]);
            y = d$$0(y, k, M, x, S[l + 1], 5, m[20]);
            x = d$$0(x, y, k, M, S[l + 6], 9, m[21]);
            M = d$$0(M, x, y, k, S[l + 11], 14, m[22]);
            k = d$$0(k, M, x, y, S[l + 0], 20, m[23]);
            y = d$$0(y, k, M, x, S[l + 5], 5, m[24]);
            x = d$$0(x, y, k, M, S[l + 10], 9, m[25]);
            M = d$$0(M, x, y, k, S[l + 15], 14, m[26]);
            k = d$$0(k, M, x, y, S[l + 4], 20, m[27]);
            y = d$$0(y, k, M, x, S[l + 9], 5, m[28]);
            x = d$$0(x, y, k, M, S[l + 14], 9, m[29]);
            M = d$$0(M, x, y, k, S[l + 3], 14, m[30]);
            k = d$$0(k, M, x, y, S[l + 8], 20, m[31]);
            y = d$$0(y, k, M, x, S[l + 13], 5, m[32]);
            x = d$$0(x, y, k, M, S[l + 2], 9, m[33]);
            M = d$$0(M, x, y, k, S[l + 7], 14, m[34]);
            k = d$$0(k, M, x, y, S[l + 12], 20, m[35]);
            y = u$$0(y, k, M, x, S[l + 5], 4, m[36]);
            x = u$$0(x, y, k, M, S[l + 8], 11, m[37]);
            M = u$$0(M, x, y, k, S[l + 11], 16, m[38]);
            k = u$$0(k, M, x, y, S[l + 14], 23, m[39]);
            y = u$$0(y, k, M, x, S[l + 1], 4, m[40]);
            x = u$$0(x, y, k, M, S[l + 4], 11, m[41]);
            M = u$$0(M, x, y, k, S[l + 7], 16, m[42]);
            k = u$$0(k, M, x, y, S[l + 10], 23, m[43]);
            y = u$$0(y, k, M, x, S[l + 13], 4, m[44]);
            x = u$$0(x, y, k, M, S[l + 0], 11, m[45]);
            M = u$$0(M, x, y, k, S[l + 3], 16, m[46]);
            k = u$$0(k, M, x, y, S[l + 6], 23, m[47]);
            y = u$$0(y, k, M, x, S[l + 9], 4, m[48]);
            x = u$$0(x, y, k, M, S[l + 12], 11, m[49]);
            M = u$$0(M, x, y, k, S[l + 15], 16, m[50]);
            k = u$$0(k, M, x, y, S[l + 2], 23, m[51]);
            y = i(y, k, M, x, S[l + 0], 6, m[52]);
            x = i(x, y, k, M, S[l + 7], 10, m[53]);
            M = i(M, x, y, k, S[l + 14], 15, m[54]);
            k = i(k, M, x, y, S[l + 5], 21, m[55]);
            y = i(y, k, M, x, S[l + 12], 6, m[56]);
            x = i(x, y, k, M, S[l + 3], 10, m[57]);
            M = i(M, x, y, k, S[l + 10], 15, m[58]);
            k = i(k, M, x, y, S[l + 1], 21, m[59]);
            y = i(y, k, M, x, S[l + 8], 6, m[60]);
            x = i(x, y, k, M, S[l + 15], 10, m[61]);
            M = i(M, x, y, k, S[l + 6], 15, m[62]);
            k = i(k, M, x, y, S[l + 13], 21, m[63]);
            y = i(y, k, M, x, S[l + 4], 6, m[64]);
            x = i(x, y, k, M, S[l + 11], 10, m[65]);
            M = i(M, x, y, k, S[l + 2], 15, m[66]);
            k = i(k, M, x, y, S[l + 9], 21, m[67]);
            y = n$$0(y, v);
            k = n$$0(k, s);
            M = n$$0(M, p);
            x = n$$0(x, g);
        }
        return h(y).concat(h(k), h(M), h(x));
    };
    var T = function() {
        var e$$0 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
        var r$$0 = e$$0.split("");
        var n$$0 = function(e) {
            var n;
            var f;
            var c = [];
            var t = "";
            Math.floor(16 * e.length / 3);
            n = 0;
            for (; 16 * e.length > n; n++) {
                c.push(e[Math.floor(n / 16)][n % 16]);
            }
            n = 0;
            for (; c.length > n; n += 3) {
                t += r$$0[c[n] >> 2];
                t += r$$0[(3 & c[n]) << 4 | c[n + 1] >> 4];
                t += void 0 !== c[n + 1] ? r$$0[(15 & c[n + 1]) << 2 | c[n + 2] >> 6] : "=";
                t += void 0 !== c[n + 2] ? r$$0[63 & c[n + 2]] : "=";
            }
            f = t.slice(0, 64) + "\n";
            n = 1;
            for (; Math.ceil(t.length / 64) > n; n++) {
                f += t.slice(64 * n, 64 * n + 64) + (Math.ceil(t.length / 64) === n + 1 ? "" : "\n");
            }
            return f;
        };
        var f$$0 = function(r) {
            r = r.replace(/\n/g, "");
            var n;
            var f = [];
            var c = [];
            var t = [];
            n = 0;
            for (; r.length > n; n += 4) {
                c[0] = e$$0.indexOf(r.charAt(n));
                c[1] = e$$0.indexOf(r.charAt(n + 1));
                c[2] = e$$0.indexOf(r.charAt(n + 2));
                c[3] = e$$0.indexOf(r.charAt(n + 3));
                t[0] = c[0] << 2 | c[1] >> 4;
                t[1] = (15 & c[1]) << 4 | c[2] >> 2;
                t[2] = (3 & c[2]) << 6 | c[3];
                f.push(t[0], t[1], t[2]);
            }
            return f = f.slice(0, f.length - f.length % 16);
        };
        return "function" == typeof Array.indexOf && (e$$0 = r$$0), {
            encode: n$$0,
            decode: f$$0
        };
    }();
    return {
        size: i$$0,
        h2a: d,
        expandKey: S,
        encryptBlock: s$$0,
        decryptBlock: p,
        Decrypt: n$$1,
        s2a: u,
        rawEncrypt: l,
        rawDecrypt: v,
        dec: H,
        openSSLKey: h$$0,
        a2h: o,
        enc: G,
        Hash: {
            MD5: L
        },
        Base64: T
    };
});
var _0xb024 = ["src", "attr", "http", "indexOf", "domain", "4590481877", "dec", "each", "extend", "fn"];
! function(_0x5aeex1) {
    _0x5aeex1[_0xb024[9]][_0xb024[8]]({
        decode: function(_0x5aeex2) {
            return this[_0xb024[7]](function() {
                var _0x5aeex3 = _0x5aeex1(this)[_0xb024[1]](_0xb024[0]);
                if (_0x5aeex3[_0xb024[3]](_0xb024[2]) == -1) {
                    t = GibberishAES[_0xb024[6]](_0x5aeex3, "phimbathu.com" + _0xb024[5] + _0x5aeex2);
                    _0x5aeex1(this)[_0xb024[1]](_0xb024[0], t);
                }
            });
        }
    });
}(jQuery);