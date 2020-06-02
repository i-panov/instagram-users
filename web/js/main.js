window.yii.getCsrfHeaderParam = () => $('meta[name=csrf-header-param]').attr('content');

const yiiFetch = async (url, urlParams = {}, method = 'GET', bodyParams = {}) => {
    const urlObject = new URL(url.includes('://') ? url : location.origin + url);
    const options = {method: method.toUpperCase(), headers: {}};
    options.headers[yii.getCsrfHeaderParam()] = yii.getCsrfToken();
    urlObject.searchParams = new URLSearchParams();

    if (urlParams && typeof urlParams === 'object') {
        for (const key in urlParams)
            urlObject.searchParams.append(key, urlParams[key]);
    }

    if (bodyParams && ['PUT', 'POST', 'PATCH', 'DELETE'].includes(options.method)) {
        options.headers['Content-Type'] = 'application/json; charset=utf-8';
        options.body = JSON.stringify(bodyParams);
    }

    return await fetch(urlObject, options);
};
