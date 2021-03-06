define(['backbone',
        'text!templates/registration/registrationHeaderTemplate.html'],
    function (Backbone,
              template) {
        'use strict';
        var View = Backbone.View.extend({
            el: '#head',
            template: _.template(template),

            initialize: function () {
                this.render();
            },

            render: function () {
                this.$el.html(this.template());
            }
        });
        return View;
    });