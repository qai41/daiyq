var graceChecker = require("../../graceUI/jsTools/graceChecker.js");
var graceRequest = require("../../graceUI/jsTools/request.js");
const app = getApp()
Page({
  data: {
    numIndex: 0,
    num: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15'],
    dateValue: "请选择",
    timeValue:"请选择",
    palce:"",
    latitude:"",
    longitude:"",
    graceFullLoading: false
  },
  onLoad: function (options) {
    app.checkLogin('../yue/index', '2');
  },
  bindPickerChange: function (e) {
    this.setData({numIndex : e.detail.value});
  },
  bindDateChange: function (e) {
    this.setData({ dateValue: e.detail.value });
  },
  bindTimeChange: function (e) {
    this.setData({ timeValue: e.detail.value });
  },
  bindPlaceChange: function (e) {
    const _self = this;
    wx.chooseLocation({
      success(res) {
        _self.setData({ 
          palce: res.name,
          latitude: res.latitude,
          longitude: res.longitude
        });
      }
    })
  },
  formSubmit: function (e) {
    var _self = this;
    _self.setData({ graceFullLoading: true });
    //定义表单规则
    var rule = [
      { name: "title", checkType: "notnull", checkRule: "", errorMsg: "标题不能为空" },
      { name: "bd", checkType: "notsame", checkRule: "请选择", errorMsg: "请选择运动日期" },
      { name: "bt", checkType: "notsame", checkRule: "请选择", errorMsg: "请选择运动时间" },
      { name: "place", checkType: "notnull", checkRule: "", errorMsg: "请选择打球地点" },
    ];
    //进行表单检查
    var formData = e.detail.value;
    var checkRes = graceChecker.check(formData, rule);
    
    if (checkRes) {
      const data = e.detail.value;
      data.uid = wx.getStorageSync('SUID');
      data.latitude = this.data.latitude;
      data.longitude = this.data.longitude;
      graceRequest.post(
        'index/buildyue',
        data,
        'form',
        {},
        function (res) {
          _self.setData({ graceFullLoading: false });
          wx.navigateTo({
            url: '../join/index?yueid=' + res.data
          })
        }
      );
    } else {
      wx.showToast({ title: graceChecker.error, icon: "none" });
      _self.setData({ graceFullLoading: false });
    }
    
  }
})