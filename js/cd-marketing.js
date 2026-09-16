(function () {
    'use strict';

    if (window.__cdMarketingLoaded) {
        return;
    }
    window.__cdMarketingLoaded = true;

    var COOKIE_NAME = 'cd_marketing';
    var COOKIE_DAYS = 365;

    var UTM_PARAMS = [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'utm_id'
    ];

    var AD_ID_PARAMS = [
        'gclid',
        'fbclid',
        'msclkid',
        'li_fat_id',
        'ttclid',
        'twclid',
        'rdt_cid'
    ];

    function getCookie(name) {
        var cookies = document.cookie.split('; ');
        var i;
        var parts;
        var key;

        for (i = 0; i < cookies.length; i++) {
            parts = cookies[i].split('=');
            key = parts.shift();

            if (key === name) {
                try {
                    return decodeURIComponent(parts.join('='));
                } catch (error) {
                    return parts.join('=');
                }
            }
        }

        return null;
    }

    function setCookie(name, value, days) {
        var maxAge = days * 24 * 60 * 60;
        var cookie = name + '=' + encodeURIComponent(value) +
            '; Max-Age=' + maxAge +
            '; Path=/' +
            '; SameSite=Lax';

        if (location.protocol === 'https:') {
            cookie += '; Secure';
        }

        document.cookie = cookie;
    }

    function dropCookie(name) {
        document.cookie = name + '=; Max-Age=0; Path=/; SameSite=Lax';
    }

    function generateVisitorId() {
        if (window.crypto && crypto.randomUUID) {
            return crypto.randomUUID();
        }

        return 'v_' +
            Date.now().toString(36) +
            '_' +
            Math.random().toString(36).substring(2, 12);
    }

    function getMarketingData() {
        var cookie = getCookie(COOKIE_NAME);

        if (!cookie) {
            return {};
        }

        try {
            return JSON.parse(cookie);
        } catch (error) {
            return {};
        }
    }

    function getUrlParams() {
        var params;
        var data = {};
        var i;
        var value;

        try {
            params = new URLSearchParams(window.location.search);
        } catch (error) {
            return data;
        }

        for (i = 0; i < UTM_PARAMS.length; i++) {
            value = params.get(UTM_PARAMS[i]);
            if (value) {
                data[UTM_PARAMS[i]] = value;
            }
        }

        for (i = 0; i < AD_ID_PARAMS.length; i++) {
            value = params.get(AD_ID_PARAMS[i]);
            if (value) {
                data.ad_id = value;
                data.ad_id_param = AD_ID_PARAMS[i];
                break;
            }
        }

        return data;
    }

    function detectAdType(data) {
        var source;
        var sourceMap;
        var idMap;

        if (!data) {
            return null;
        }

        source = (data.utm_source || '').toLowerCase();

        sourceMap = {
            google: 'google_ads',
            facebook: 'meta_ads',
            instagram: 'meta_ads',
            meta: 'meta_ads',
            linkedin: 'linkedin_ads',
            bing: 'bing_ads',
            microsoft: 'bing_ads',
            tiktok: 'tiktok_ads',
            twitter: 'x_ads',
            x: 'x_ads',
            reddit: 'reddit_ads'
        };

        if (sourceMap[source]) {
            return sourceMap[source];
        }

        idMap = {
            gclid: 'google_ads',
            fbclid: 'meta_ads',
            msclkid: 'bing_ads',
            li_fat_id: 'linkedin_ads',
            ttclid: 'tiktok_ads',
            twclid: 'x_ads',
            rdt_cid: 'reddit_ads'
        };

        return idMap[data.ad_id_param] || null;
    }

    function hasNewMarketingData(data) {
        var keys;
        var i;
        var key;

        if (!data) {
            return false;
        }

        keys = UTM_PARAMS.concat(['ad_id', 'ad_id_param', 'ad_type']);

        for (i = 0; i < keys.length; i++) {
            key = keys[i];
            if (data[key] !== null && data[key] !== undefined && data[key] !== '') {
                return true;
            }
        }

        return false;
    }

    function buildStoredData(visitorId, firstSeenAt, currentData, adType) {
        var now = new Date().toISOString();

        return {
            visitor_id: visitorId,
            first_seen_at: firstSeenAt || now,
            utm_source: currentData.utm_source || null,
            utm_medium: currentData.utm_medium || null,
            utm_campaign: currentData.utm_campaign || null,
            utm_term: currentData.utm_term || null,
            utm_content: currentData.utm_content || null,
            utm_id: currentData.utm_id || null,
            ad_type: adType,
            ad_id: currentData.ad_id || null,
            ad_id_param: currentData.ad_id_param || null,
            landing_page: window.location.pathname,
            landing_url: window.location.href,
            last_seen_at: now
        };
    }

    function shouldSkipForm(form) {
        var action;
        var method;

        if (!form || !form.tagName || form.tagName.toLowerCase() !== 'form') {
            return true;
        }

        method = (form.getAttribute('method') || 'get').toLowerCase();
        if (method === 'get') {
            return true;
        }

        action = (form.getAttribute('action') || '').toLowerCase();
        if (action.indexOf('paypal.com') !== -1 || action.indexOf('paypalobjects.com') !== -1) {
            return true;
        }

        if (form.querySelector('input[name="cmd"]')) {
            return true;
        }

        if ((form.getAttribute('name') || '').toLowerCase().indexOf('paypal') !== -1) {
            return true;
        }

        if ((form.id || '').toLowerCase().indexOf('paypal') !== -1) {
            return true;
        }

        return false;
    }

    function upsertHidden(form, name, value) {
        var input = form.querySelector('input[name="' + name + '"]');

        if (value === null || value === undefined) {
            value = '';
        }

        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            form.appendChild(input);
        }

        input.value = String(value);
    }

    function injectIntoForm(form) {
        var data;
        var jsonValue;

        if (shouldSkipForm(form)) {
            return;
        }

        data = window.ChineseDumpsMarketing || getMarketingData() || {};
        jsonValue = '';

        try {
            jsonValue = JSON.stringify(data);
        } catch (error) {
            jsonValue = '';
        }

        upsertHidden(form, 'cd_visitor_id', data.visitor_id || '');
        upsertHidden(form, 'cd_utm_source', data.utm_source || '');
        upsertHidden(form, 'cd_utm_medium', data.utm_medium || '');
        upsertHidden(form, 'cd_utm_campaign', data.utm_campaign || '');
        upsertHidden(form, 'cd_utm_term', data.utm_term || '');
        upsertHidden(form, 'cd_utm_content', data.utm_content || '');
        upsertHidden(form, 'cd_utm_id', data.utm_id || '');
        upsertHidden(form, 'cd_ad_type', data.ad_type || '');
        upsertHidden(form, 'cd_ad_id', data.ad_id || '');
        upsertHidden(form, 'cd_ad_id_param', data.ad_id_param || '');
        upsertHidden(form, 'cd_marketing_json', jsonValue);
    }

    function injectAllForms() {
        var forms = document.getElementsByTagName('form');
        var i;

        for (i = 0; i < forms.length; i++) {
            injectIntoForm(forms[i]);
        }
    }

    function onReady(fn) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            fn();
        }
    }

    function initMarketingTracking() {
        var storedData = getMarketingData();
        var currentData = getUrlParams();
        var visitorId;
        var firstSeenAt;
        var adType;

        if (hasNewMarketingData(currentData)) {
            visitorId = storedData.visitor_id || generateVisitorId();
            firstSeenAt = storedData.first_seen_at || new Date().toISOString();
            adType = detectAdType(currentData);

            dropCookie(COOKIE_NAME);
            storedData = buildStoredData(visitorId, firstSeenAt, currentData, adType);
            setCookie(COOKIE_NAME, JSON.stringify(storedData), COOKIE_DAYS);
        } else if (hasNewMarketingData(storedData)) {
            setCookie(COOKIE_NAME, JSON.stringify(storedData), COOKIE_DAYS);
        }

        window.ChineseDumpsMarketing = storedData;
    }

    initMarketingTracking();

    onReady(function () {
        injectAllForms();
    });

    document.addEventListener('submit', function (event) {
        if (event && event.target) {
            injectIntoForm(event.target);
        }
    }, true);

    window.cdInjectMarketingFields = injectAllForms;
})();
