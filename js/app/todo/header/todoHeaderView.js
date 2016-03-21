define(['backbone', 'text!templates/todoTemplates/todoHeaderTemplate.html'], function(Backbone, template){
  var View = Backbone.View.extend({
    el: '#head',
    template: _.template(template),

    initialize: function() {
      this.render();
    },

    render: function(){
      this.$el.html(this.template(  ));
    }
  });
  return View;
});