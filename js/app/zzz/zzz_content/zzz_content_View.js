define(
    [
      'backbone',
      'text!templates/zzz_content/zzz_content_Template.html',
      'app/simpAlert/simpAlertView',
      'app/simpAlert/simpAlertModel'
    ],
    function(Backbone, template, simpAlertView, simpAlertModel){
      var that = this;

      var sl = {};
      sl.q = 'a';
      var View = Backbone.View.extend({
        el: '#content',
        template: _.template(template),
        events:{
          'click #submitButton': 'loginSubmit'
        },

        initialize: function() {
          this.modelAlert = new simpAlertModel(); // создаем модель для информационных сообщений
          this.render();  //рендер контентной части
          this.model.on('invalid', function(model, error) { //привязываем вывод информационного сообщения на валидацию модели
            this.modelAlert.set('textAlert', error);
            new simpAlertView({model: this.modelAlert}).render();
          }.bind(this));
        },

        loginSubmit: function() {
          var strLogin = this.$('#login').val().trim(),
              strPassword = this.$('#password').val().trim();
          this.model.set({login: strLogin, password: strPassword});
          this.model.save("", "", {
            error: function() {
              this.modelAlert.set('textAlert', 'Вы ошиблись при вводе логина или пароля.');
              new simpAlertView({model: this.modelAlert}).render();
            }.bind(this)
          });
        },

        render: function(){
          this.$el.html(this.template( this.model.toJSON() ));
        }
      });
      return View;
    });