import './bootstrap';
import { createApp } from "vue";
import LoginComponent from "./Components/LoginComponent.vue";

const app = createApp({});

app.component("login", LoginComponent);

app.mount("#app");
