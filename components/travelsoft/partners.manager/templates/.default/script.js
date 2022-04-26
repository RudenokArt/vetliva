
/**
 * @package partners.manager
 * @author dimabresky
 */

if (typeof VetlivaPartnersManager !== "function") {

    (function () {

        async function __runAsync(context, callbacks) {

            for (var i = 0; i < callbacks.length; i++) {

                await context[callbacks[i]]();
            }

            return new Promise(function (resolve) {
                resolve(true);
            });
        }

        /**
         * @param {DOMElement} row
         * @returns {Boolean}
         */
        function __filterByActive(row) {

            var filter = document.querySelector('.filter__by-active');

            if (filter.value === "active") {
                return row.dataset.active === 'Y';
            } else if (filter.value === "unactive") {
                return row.dataset.active === 'N';
            }

            return true;
        }

        /**
         * @param {DOMElement} row
         * @returns {Boolean}
         */
        function __filterByDateCreate(row) {

            var filter = document.querySelector('.filter__by-date-create');

            if (filter.value) {
                return row.dataset.dateCreate.indexOf(filter.value) > -1;
            }

            return true;

        }

        /**
         * @param {DOMElement} row
         * @returns {Boolean}
         */
        function __filterByDateChange(row) {
            var filter = document.querySelector('.filter__by-date-change');

            if (filter.value) {
                return row.dataset.dateChange.indexOf(filter.value) > -1;
            }

            return true;
        }
        
        /**
         * @param {DOMElement} row
         * @returns {Boolean}
         */
        function __filterExcursiontoursByMultipledays(row) {

            var filter = document.querySelector('.filter__by-multipledays');

            if (filter.value === "multiple-days") {
                return row.dataset.isMultiple === 'Y';
            } else if (filter.value === "one-day") {
                return row.dataset.isMultiple === 'N';
            }

            return true;
        }
        
        /**
         * @param {DOMElement} row
         * @returns {Boolean}
         */
        function __filterByName(row) {

            var filter = document.querySelector('.filter__by-name');
            
            if (filter.value !== '') {
                return row.dataset.name.toLowerCase().indexOf(filter.value.toLowerCase()) > -1;
            }
            
            return true;
        }

        /**
         * Инициализирует работу компонента partners.manager на странице
         * @param {Object} config
         * @returns {undefined}
         */
        function VetlivaPartnersManager(config) {

            var runFirst = ['breadcrumbsRender', 'providersSelectionRender'];

            var keys = null;

            this.config = config;

            this.objects_list = null;

            this.rooms_list = null;

            if (!this.config['partners.manager:actions-data-store']) {
                console.error('partners.manager: Не указано начальное состояние !!!');
                return false;
            }

            keys = Object.entries(this.config['partners.manager:actions-data-store']);

            if (keys[keys.length - 1][0] && keys[keys.length - 1][0] !== 'providers-selection') {
                runFirst.push(this.config['partners.manager:actions-data-store'][keys[keys.length - 1][0]]['js-render-method']);
            }

            this.runAsync(runFirst);
        }

        /**
         * Показывает preloader
         * @returns {undefined}
         */
        VetlivaPartnersManager.prototype.showPreloader = function () {

            this.config['preloader-container'].classList.remove('hidden');
        };

        /**
         * Скрывает preloader
         * @returns {undefined}
         */
        VetlivaPartnersManager.prototype.hidePreloader = function () {

            this.config['preloader-container'].classList.add('hidden');
        };

        /**
         * Отрисовывает хлебные крошки
         * @returns {Promise}
         */
        VetlivaPartnersManager.prototype.breadcrumbsRender = function () {

            var __this = this;

            return new Promise(function (resolve, reject) {

                var node = __this.config['breadcrumbs-container'];

                __this.sendRequest({

                    data: {action: 'breadcrumbs'},
                    success: function (response) {

                        if (response.errors) {
                            reject(false);
                        } else {
                            node.innerHTML = response['action-content'];
                            resolve(true);
                        }


                    }
                });
            });

        };

        /**
         * Отрисовывает select выбора поставщика
         * @returns {Promise}
         */
        VetlivaPartnersManager.prototype.providersSelectionRender = function () {

            var __this = this;

            return new Promise(function (resolve, reject) {

                var node = __this.config['selection-provider-container'];

                __this.sendRequest({

                    data: {action: 'providers-selection'},
                    success: function (response) {

                        if (response.errors) {
                            reject(false);
                        } else {
                            node.innerHTML = response['action-content'];
                            $('.partners-manager-selection-provider__select').select2();
                            resolve(true);
                        }
                    }
                });
            });

        };

        /**
         * Отрисовывает список объектов
         * @returns {Promise}
         */
        VetlivaPartnersManager.prototype.objectsListRender = function () {

            var __this = this;

            return new Promise(function (resolve, reject) {

                var node = __this.config['action-container'];

                __this.sendRequest({

                    data: {action: 'objects-list'},
                    success: function (response) {

                        if (response.errors) {
                            reject(false);
                        } else {
                            node.innerHTML = response['action-content'];
                            __this.objects_list = node.querySelectorAll('.objects-list__row');
                            resolve(true);
                        }
                    }
                });
            });

        };

        /**
         * Отрисовывает номерной фонд по объекту
         * @returns {Promise}
         */
        VetlivaPartnersManager.prototype.roomsListRender = function () {
            var __this = this;

            return new Promise(function (resolve, reject) {

                var node = __this.config['action-container'];

                __this.sendRequest({

                    data: {action: 'rooms-list'},
                    success: function (response) {

                        if (response.errors) {
                            reject(false);
                        } else {
                            node.innerHTML = response['action-content'];
                            __this.rooms_list = node.querySelectorAll('.rooms-list__row');
                            resolve(true);
                        }
                    }
                });
            });
        };

        /**
         * Отрисовывает номерной фонд по объекту
         * @returns {Promise}
         */
        VetlivaPartnersManager.prototype.simplePricesManageRender = function () {
            var __this = this;

            return new Promise(function (resolve, reject) {

                var node = __this.config['action-container'];

                __this.sendRequest({

                    data: {action: 'simple-prices-manage'},
                    success: function (response) {

                        if (response.errors) {
                            reject(false);
                        } else {
                            node.innerHTML = response['action-content'];
                            $('#spm-filter-select').select2();
                            resolve(true);
                        }
                    }
                });
            });
        };

        /**
         * @returns {undefined}
         */
        VetlivaPartnersManager.prototype.showProvidersList = function () {

            this.config['action-container'].innerHTML = '';

            this.config['partners.manager:actions-data-store'] = {
                'providers-selection': this.config['partners.manager:actions-data-store']['providers-selection']
            };

            this.config['partners.manager:actions-data-store']['providers-selection']['provider-id'] = 0;

            this.runAsync(['breadcrumbsRender', 'providersSelectionRender']);
        };

        /**
         * @returns {undefined}
         */
        VetlivaPartnersManager.prototype.showObjectsList = function () {

            var provider_id = document.querySelector('select.partners-manager-selection-provider__select').value;

            this.config['action-container'].innerHTML = '';
            if (provider_id <= 0) {
                this.config['partners.manager:actions-data-store'] = {
                    'providers-selection': {'provider-id': 0}
                };
                this.runAsync(['breadcrumbs']);
            } else {
                this.config['partners.manager:actions-data-store'] = {
                    'providers-selection': {
                        'provider-id': provider_id,
                        'title': this.config['partners.manager:actions-data-store']['providers-selection'].title,
                        'js-render-method': this.config['partners.manager:actions-data-store']['providers-selection']['js-render-method'],
                        'js-breadcrumbs-handler-method': this.config['partners.manager:actions-data-store']['providers-selection']['js-breadcrumbs-handler-method']
                    },
                    'objects-list': {
                        'provider-id': provider_id,
                        'object-id': 0,
                        'js-render-method': 'objectsListRender',
                        'js-breadcrumbs-handler-method': 'showObjectsList',
                        'title': 'Список объектов'
                    }
                };
                this.runAsync(['breadcrumbsRender', 'objectsListRender']);
            }

        };

        /**
         * @param {Number} object_id
         * @returns {undefined}
         */
        VetlivaPartnersManager.prototype.showRoomsList = function (object_id) {

            this.config['action-container'].innerHTML = '';
            this.config['partners.manager:actions-data-store'] = {
                'providers-selection': this.config['partners.manager:actions-data-store']['providers-selection'],
                'objects-list': this.config['partners.manager:actions-data-store']['objects-list'],
                'rooms-list': {
                    'object-id': object_id,
                    'provider-id': this.config['partners.manager:actions-data-store']['providers-selection']['provider-id'],
                    'title': 'Номерной фонд',
                    'js-render-method': 'roomsListRender',
                    'js-breadcrumbs-handler-method': 'showRoomsList'
                }
            };

            this.runAsync(['breadcrumbsRender', 'roomsListRender']);

        };

        /**
         * @param {Number} object_id
         * @returns {undefined}
         */
        VetlivaPartnersManager.prototype.showSimplePricesManage = function (object_id) {
            var services_select = document.getElementById('spm-filter-select')
            var date_from = document.getElementById('spm-filter-date-from');
            var date_to = document.getElementById('spm-filter-date-to');
            
            this.config['action-container'].innerHTML = '';
            this.config['partners.manager:actions-data-store'] = {
                'providers-selection': this.config['partners.manager:actions-data-store']['providers-selection'],
                'objects-list': this.config['partners.manager:actions-data-store']['objects-list'],
                'simple-prices-manage': {
                    'provider-id': this.config['partners.manager:actions-data-store']['providers-selection']['provider-id'],
                    'service-id': (function (services_select) {
                        return services_select && services_select.value ? services_select.value : 0;
                    })(services_select),
                    'object-id': object_id,
                    'date-from': (function (date) {
                        var date_from = new Date();
                        if (date && date.value) {
                            return date.value;
                        } else {
                            return `${date_from.getUTCDate() >= 10 ? date_from.getUTCDate() : `0${date_from.getUTCDate()}`}.${date_from.getUTCMonth() + 1 >= 10 ? date_from.getUTCMonth() + 1 : `0${date_from.getUTCMonth() + 1}`}.${date_from.getUTCFullYear()}`;
                        }
                    })(date_from),
                    'date-to': (function (date) {
                        var date_to = new Date((86400000 * 20) + (new Date()).getTime());
                        if (date && date.value) {
                            return date.value;
                        } else {
                            return `${date_to.getUTCDate() >= 10 ? date_to.getUTCDate() : `0${date_to.getUTCDate()}`}.${date_to.getUTCMonth() + 1 >= 10 ? date_to.getUTCMonth() + 1 : `0${date_to.getUTCMonth() + 1}`}.${date_to.getUTCFullYear()}`;
                        }
                    })(date_to),
                    'title': 'Упращенный режим просмотра цен и наличия мест',
                    'js-render-method': 'simplePricesManageRender',
                    'js-breadcrumbs-handler-method': 'showSimplePricesManage'
                }
            };

            this.runAsync(['breadcrumbsRender', 'simplePricesManageRender']);
        };

        /**
         * Отправка ajax-запроса
         * @param {Object} config
         * @returns {undefined}
         */
        VetlivaPartnersManager.prototype.sendRequest = function (config) {

            var __this = this;

            config = config || {};

            config.data = config.data || {};

            config.data.sessid = BX.bitrix_sessid();

            config.data['partners_manager_actions_data_store'] = __this.config['partners.manager:actions-data-store'];

            if (typeof config.beforeSend === "function") {
                config.beforeSend();
            }

            BX.ajax.post(this.config['ajax-url'], config.data, function (response) {
                response = JSON.parse(response);
                if (typeof config.success === "function") {
                    config.success(response);
                }
            });
        };

        /**
         * Запускает асинхронные функции одну за другой
         * @param {Array} callbacks
         * @returns {undefined}
         */
        VetlivaPartnersManager.prototype.runAsync = function (callbacks) {

            var __this = this;

            __this.showPreloader();
            __runAsync(__this, callbacks).then(function () {
                __this.hidePreloader();
            }, function (err) {
                console.error(err);
                __this.hidePreloader();
            });
        };
        
        /**
         * Фильтрация объектов размещения и санаториев
         * @returns {undefined}
         */
        VetlivaPartnersManager.prototype.filterObjectsList = function () {

            this.objects_list.forEach(function (row) {
                if (
                        __filterByActive(row) &&
                        __filterByDateCreate(row) &&
                        __filterByDateChange(row))
                {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });


        };
        
        /**
         * Фильтрация экскурсилнных туров
         * @returns {undefined}
         */
        VetlivaPartnersManager.prototype.filterExcursiontoursList = function () {

            this.objects_list.forEach(function (row) {
                if (
                        __filterByActive(row) &&
                        __filterExcursiontoursByMultipledays(row) &&
                        __filterByDateCreate(row) &&
                        __filterByDateChange(row))
                {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });


        };
        
        /**
         * Фильтрация трансферов
         * @returns {undefined}
         */
        VetlivaPartnersManager.prototype.filterTransfersList = function () {

            this.objects_list.forEach(function (row) {
                if (
                        __filterByName(row)
                ) {
                    row.classList.remove('hidden');
                } else {
                    row.classList.add('hidden');
                }
            });


        };

        window.VetlivaPartnersManager = VetlivaPartnersManager;

    })();

}