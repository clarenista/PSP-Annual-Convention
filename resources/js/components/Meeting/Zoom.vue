<template>
  <div class="iframe-container"></div>
</template>
<script>
import { ZoomMtg } from "@zoomus/websdk";
export default {
  mounted() {
    this.init();
  },
  methods: {
    async init() {
      await this.zoomMtgInit();
      this.getJoinConfig().then((result) => {
        this.zoomJoin(result.data);
      });
    },
    zoomMtgInit() {
      ZoomMtg.setZoomJSLib(
        "https://dmogdx0jrul3u.cloudfront.net/1.9.1/lib",
        "/av"
      );
      ZoomMtg.preLoadWasm();
      ZoomMtg.prepareWebSDK();
    },
    getJoinConfig() {
      return axios.get("/api/v1/guests/zoom/join/config?api_token=123123");
    },
    zoomJoin(config) {
      ZoomMtg.init({
        debug: false,
        leaveUrl: "http://psp.com/",
        success: () => {
          ZoomMtg.join(config);
        },
      });
    },
  },
};
</script>
<style scoped>
</style>

