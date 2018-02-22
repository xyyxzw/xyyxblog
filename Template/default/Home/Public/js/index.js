"use strict";

function getWidthLeft(t, o) { var e = t.position().left,
        i = t.width(),
        n = { left: e, width: i }; return o ? void $(".b-nav-mobile").stop().animate({ left: e, width: i }, 300) : n }

function login() { $("#b-modal-login").modal("show"), setCookie("this_url", window.location.href) }

function logout() { $.post(logoutUrl), setTimeout(function() { location.replace(location) }, 500) }

function goTop() { return $("body,html").animate({ scrollTop: 0 }, 500), !1 }

function setCookie(t, o, e) { if (e) { var i = new Date;
        i.setTime(i.getTime() + 24 * e * 60 * 60 * 1e3); var n = "; expires=" + i.toGMTString() } else var n = "";
    document.cookie = t + "=" + o + n + "; path=/" }

function getCookie(t) { for (var o = t + "=", e = document.cookie.split(";"), i = 0; i < e.length; i++) { for (var n = e[i];
            " " == n.charAt(0);) n = n.substring(1, n.length); if (0 == n.indexOf(o)) return n.substring(o.length, n.length) } return null }

function deleteCookie(t) { setCookie(t, "", -1) }

function recordId(t, o) { return setCookie("cid", 0), setCookie("tid", 0), setCookie("search_word", 0), "index" != t && "/" != t && setCookie(t, o), !0 } $(function() {
    if ("block" == $(".b-nav-mobile").css("display")) {
        var t = getWidthLeft($(".b-nav-active"), !1);
        $(".b-nav-mobile").css({ width: t.width, left: t.left })
    }
    $(".b-nav-parent li").hover(function() { getWidthLeft($(this), !0) }, function() { getWidthLeft($(".b-nav-active"), !0) }), $(".b-article iframe").width("95%"), $(window).scroll(function(t) { $(window).scrollTop() > 200 ? ($(".go-top").show(), $(".go-top").removeClass("animated rotateOut"), $(".go-top").addClass("animated rotateIn")) : ($(".go-top").removeClass("animated rotateIn"), $(".go-top").addClass("animated rotateOut")) }), window.innerWidth >= 768 && $(window).scroll(function(t) { $(window).scrollTop() > 100 ? $("#b-public-nav").stop().animate({ "padding-top": "0px", "padding-bottom": "0px" }, 100) : $("#b-public-nav").stop().animate({ "padding-top": "5px", "padding-bottom": "5px" }, 100) }), $(".b-page .first,.num,.end").addClass("hidden-xs"), $(".b-page .rows").addClass("hidden-xs"), $(".b-chat-middle").height($(".b-chat").height()), $(".b-arrows-right1,.b-arrows-right2").each(function(t, o) { $(o).css("top", $(o).parent(".b-chat-one").height() / 2.5) }), $.each($(".js-head-img"), function(t, o) {
        var e = $(o).attr("_src");
        $(o).attr("src", e)
    })
});