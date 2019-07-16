// graceUI/components/graceBottomDialog.js
Component({
  properties: {
    show: {
      type: Boolean,
      value: false
    }
  },
  data: {

  },
  methods: {
    closeDialog: function(){
      this.triggerEvent('closeDialog');
    },
    stopFun: function(){}
  },
  options: {
    multipleSlots: true
  }
})
