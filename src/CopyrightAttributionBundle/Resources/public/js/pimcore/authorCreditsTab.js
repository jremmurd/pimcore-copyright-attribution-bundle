pimcore.registerNS("pimcore.bundle.CopyrightAttributionBundle.Tab");

pimcore.bundle.CopyrightAttributionBundle.Tab = Class.create({

    title: t('copyright_attribution_menu'),
    iconCls: 'copyright_attribution_icon',
    src: '/admin/copyright-attribution/content',
    id: 'copyright_attribution_tab',

    initialize: function () {
        this.getLayout();
    },

    getLayout: function () {
        if (this.layout == null) {
            this.reloadButton = new Ext.Button({
                text: t("reload"),
                iconCls: "pimcore_icon_reload",
                handler: this.reload.bind(this)
            });

            this.flaticonButton = new Ext.Button({
                text: t("copyright_attribution.add-flaticon"),
                iconCls: "pimcore_icon_add",
                handler: this.addFlaticon.bind(this)
            });

            // create new panel
            this.layout = new Ext.Panel({
                id: this.id,
                title: this.title,
                iconCls: this.iconCls,
                layout: "fit",
                border: false,
                autoScroll: true,
                closable: true,
                containerScroll: true,
                html: '<iframe id="' + this.id + '_iframe" style="border:none" src="' + this.src + '" width="100%" height="99%"></iframe>',
                tbar: [
                    this.reloadButton,
                    this.flaticonButton,
                ]
            });

            // add event listener
            var layoutId = this.id;
            this.layout.on("destroy", function () {
                pimcore.globalmanager.remove(layoutId);
                pimcore.layout.refresh();
            }.bind(this));

            // add panel to pimcore panel tabs
            var tabPanel = Ext.getCmp("pimcore_panel_tabs");
            tabPanel.add(this.layout);
            tabPanel.setActiveItem(this.id);

            // update layout
            pimcore.layout.refresh();
        }
        return this.layout;
    },

    addFlaticon: function () {
        var that = this;
        var dlg = new Ext.Window({
                width: 500,
                height: 300,
                modal: true,
                autoShow: true,
                layout: 'fit',
                buttonAlign: 'left',
                bodyStyle: "display: flex; align-items: center",
                items: [
                    {
                        xtype: 'form',
                        frame: false,
                        border: 0,
                        layout: {
                            type: 'hbox',
                            align: 'middle'
                        },
                        fieldDefaults: {
                            msgTarget: 'side',
                            labelWidth: 80
                        },
                        items: [
                            {
                                xtype: 'container',
                                flex: 1,
                                padding: 10,
                                layout: {
                                    type: 'vbox',
                                    align: 'center'
                                },
                                items: [
                                    {
                                        xtype: 'textfield',
                                        width: 450,
                                        fieldLabel: t("copyright_attribution.subject"),
                                        id: this.layout.id + "_subject",
                                        value: "dataobject_icons"
                                    },
                                    {
                                        xtype: 'textfield',
                                        width: 450,

                                        fieldLabel: t("copyright_attribution.flaticon-text"),
                                        id: this.layout.id + "_flaticon-text"
                                    },
                                    {
                                        xtype: 'button',
                                        text: t("copyright_attribution.add-flaticon"),
                                        style: "margin-top: 2em",
                                        handler: function (btn, e) {

                                            var text = document.getElementById(that.layout.id + "_flaticon-text-inputEl").value;
                                            var subject = document.getElementById(that.layout.id + "_subject-inputEl").value;

                                            if (!text || !subject) {
                                                return;
                                            }

                                            Ext.Ajax.request({
                                                url: "/admin/copyright-attribution/add-flaticon",
                                                headers: {
                                                    'Content-Type': 'application/json'
                                                },
                                                params: {
                                                    text: text,
                                                    subject: subject,
                                                }
                                            })
                                                .then(function (res) {
                                                    console.log(res);
                                                    try{
                                                        var data = JSON.parse(res.responseText);
                                                        if (data.error) {
                                                            pimcore.helpers.showNotification(t("success"), t(data.error), "error");
                                                            return;
                                                        }
                                                    }catch (e) {
                                                    }

                                                    try {
                                                        pimcore.helpers.showNotification(t("success"), t("saved_successfully"), "success");
                                                        this.up('window').close();
                                                        that.reload();
                                                    } catch (e) {
                                                        console.log(e);
                                                    }

                                                }.bind(this))
                                                .otherwise(function (e) {
                                                    pimcore.helpers.showNotification(t("error"), t("saving_failed"), "error");
                                                    e.stopPropagation();
                                                });

                                        }
                                    }
                                ]
                            }
                        ]
                    }
                ]
            }
        );
    },

    reload: function () {
        try {
            document.getElementById(this.id + "_iframe").src = this.src;
        } catch (e) {
            console.log(e);
        }
    }
});
