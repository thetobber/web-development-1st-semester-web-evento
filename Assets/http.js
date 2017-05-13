var Http = function () { };

class Https {
    static request(args) {
        const _args = {
            withCredentials: false,
            async: true,
            timeout: 5000,
            headers: [],
            method: 'GET',
            body: null,
            user: null,
            password: null
        };

        return new Promise((resolve, reject) => {
            args = Utilities.extend(_args, args);

            const xhr = new XMLHttpRequest();

            xhr.open(args.method, args.url, args.async, args.user, args.password);
            xhr.timeout = args.timeout;

            for (let key in args.headers) {
                xhr.setRequestHeader(key, args.headers[key]);
            }

            xhr.addEventListener('load', function () {
                if (this.status >= 200 && this.status < 300) {
                    resolve(this.response);
                } else {
                    reject({
                        status: this.status,
                        statuxText: this.statusText
                    });
                }
            });

            xhr.addEventListener('error', function () {
                reject({
                    status: this.status,
                    statuxText: this.statusText
                });
            });

            xhr.send(options.body);
        });
    }

    static post() {

    }

    static get() {

    }

    static put() {

    }

    static delete() {

    }
}

class Utilities {
    static extend(output) {
        output = output || {};

        for (let index = 1; index < arguments.length; index++) {
            const object = arguments[index];

            if (!object) {
                continue;
            }

            for (const key in object) {
                if (object.hasOwnProperty(key)) {
                    if (typeof object[key] === 'object') {
                        output[key] = Utilities.extend(output[key], object[key]);
                    } else {
                        output[key] = object[key];
                    }
                }
            }
        }

        return output;
    }
}

/*
Http.request = function (args) {
    args.withCredentials = args.withCredentials || false;
    args.async = args.async || true;
    args.timeout = args.timeout || 5000;
    args.headers = args.headers || [];
    args.method = args.method || 'GET';
    args.body = args.body || null;
    args.user = args.user || null;
    args.password = args.password || null;

    return new Promise((resolve, reject) => {

        const request = new XMLHttpRequest();

        request.open();



    });


    return new Promise(function (resolve, reject) {
        var xhr = new XMLHttpRequest();

        xhr.open(options.method, options.url, options.async, options.user, options.password);
        xhr.timeout = options.timeout;

        for (var key in options.headers) {
            xhr.setRequestHeader(key, options.headers[key]);
        }

        xhr.onload = function () {
            if (this.status >= 200 && this.status < 300) {
                resolve(xhr.response);
            } else {
                reject({
                    status: this.status,
                    statuxText: xhr.statusText
                });
            }
        };

        xhr.onerror = function () {
            reject({
                status: this.status,
                statuxText: xhr.statusText
            });
        };

        xhr.send(options.body);
    });
};

Http.head = function (args) {
    args.method = 'HEAD';
    return Http.request(args);
};

Http.options = function (args) {
    args.method = 'OPTIONS';
    return Http.request(args);
};

Http.post = function (args) {
    args.method = 'POST';
    return Http.request(args);
};

Http.get = function (args) {
    args.method = 'GET';
    return Http.request(args);
};

Http.put = function (args) {
    args.method = 'PUT';
    return Http.request(args);
};

Http.delete = function (args) {
    args.method = 'DELETE';
    return Http.request(args);
};

/*window.addEventListener('load', function () {
    var timezone = document.querySelector('#timezone');
    var http = new Http();

    console.log(timezone);

    http.get({
        url: 'https://dawa.aws.dk/postnumre'
    })
    .then(function (response) {
        response = JSON.parse(response);
        
        var datalist = '';

        for (var i in response) {
            for (var j in response[i].kommuner) {
                var code = response[i].kommuner[j].kode;
                var road = response[i].kommuner[j].navn;

                datalist += `<option value="${code}">${code} ${road}</option>`;
            }
        }

        timezone.innerHTML = datalist;
    });
});*/