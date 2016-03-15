define(['backbone', 'text!app/newUserContent/newUserContent_Template.html'], function(Backbone, templ){
  var View = Backbone.View.extend({
    el: '#mainContetn',
    template: _.template(templ),
    render: function(){
      $(this.el).html(this.template());
      return this;
    }
  });
  return View;
});