'use strict';

function BasisMarketingFavorites(){
    this.params = null;

    this.options = {
        dataSelector: 'data-favorites-btn-toggle', // селектор для кнопок избранного
        emptyClass: 'empty', // для корзины в header
        activeClass: 'active', // для иконок на карточке товара
        loadingClass: 'loading'
    }
}

BasisMarketingFavorites.prototype = {
    init: function ()
    {
        if (!this.params)return;

        this.favoriteContainerNode = document.querySelector(this.favoriteContainer);
        if (!this.favoriteContainerNode)
            return;

        this.isChanged = false;
        this.alwaysRerender = (!(!('ALWAYS_RERENDER' in this.params) || this.params.ALWAYS_RERENDER === 'N'));

        BX.bind(
            BX(this.favoriteId),
            'click',
            BX.delegate(this.refreshFav, this)
        )

        BX.bindDelegate(
            window,
            'click',
            {
                className: `${this.favoriteId}_clear`
            },
            () => this.remove(0)
        )

        BX.addCustomEvent(window, 'OnFavoriteSend', BX.delegate(this.onSend, this));
    },

    getSelector: function (id = 0)
    {
        return id === 0 ? `[${this.options.dataSelector}]` : `[${this.options.dataSelector}="${id}"]`
    },

    handlerFavoriteIconClass: function (data)
    {
        if(!('COUNT' in data))
            return

        (+data.COUNT > 0)
            ? BX.removeClass(BX(this.favoriteId), this.options.emptyClass)
            : BX.addClass(BX(this.favoriteId), this.options.emptyClass);

        this.isChanged = true
    },

    refreshFav: function ()
    {
        if (!this.isChanged)
            return

        this.favoriteContainerNode.classList.add(this.options.loadingClass);

        BX.ajax({
            url: this.ajaxPath,
            method: 'POST',
            dataType: 'html',
            data: {
                sessid: BX.bitrix_sessid(),
                siteId: this.siteId,
                favoriteId: this.favoriteId,
                templateName: this.templateName,
            },
            onsuccess: BX.delegate(this.setFavoritesBody, this)
        });
    },

    setFavoritesBody: function (result)
    {
        this.favoriteContainerNode.innerHTML = result
        this.favoriteContainerNode.classList.remove(this.options.loadingClass)
        this.isChanged = false
    },

    onSend: function (event)
    {
        const {id} = event.data
        this.send(id, this.alwaysRerender) // if need rerender always - this.send(id, true)
    },

    send: function (id, isRerender = false)
    {
        return BX.ajax.runComponentAction(
            'vlworks:catalog.favorite',
            'send',
            {
                data: {
                    'id': id,
                }
            }
        ).then( (response) => {
            if (response.status === 'success')
            {
                const result = JSON.parse(response.data)
                this.handlerFavoriteIconClass(result)

                switch (result.MESSAGE) {
                    case 'CLEAN':
                        this.toggleBtn();
                        break;
                    case 'DEL':
                        this.toggleBtn(result.ID);
                        break;
                    case 'ADD':
                        this.toggleBtn(result.ID, 'add')
                        break;
                }

                if (isRerender) this.refreshFav()
            }
        })
    },

    remove: function (id)
    {
        this.send(id, true)
    },

    toggleBtn: function (id = 0, type = 'remove')
    {
        const selector = this.getSelector(id)

        const btnList = document.querySelectorAll(selector);
        if (!btnList)
            return;

        switch (type) {
            case 'add':
                btnList.forEach( btn => btn.classList.add(this.options.activeClass));
                break;
            default:
                btnList.forEach( btn => btn.classList.remove(this.options.activeClass));
        }
    },
}