// graceUI/components/graceSpread.js
Component({
  properties: {
    height: {
      type: String,
      value: "300px"
    },
    btnTxt: {
      type: String,
      value: "展开阅读全文"
    }
  },
  data: {
    isBtn: true
  },
  methods: {
    spreadContent: function () {
      this.setData({height:'auto', isBtn : false});
    }
  }
})
