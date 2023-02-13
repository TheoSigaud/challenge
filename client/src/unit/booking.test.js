/**
 *@vitest-environment happy-dom
**/

import Home from "@/views/HomeView.vue";
import Login from "@/views/LoginView.vue";
import {mount} from "@vue/test-utils";
import {describe} from "vitest";

describe("Test Home", () => {
    it("display cards", async () => {
        const wrapperLogin = mount(Login)
        const textEmail = wrapperLogin.find('input[type="email"]')
        const textPassword = wrapperLogin.find('input[type="password"]')
        const button = wrapperLogin.find('button')

        await textEmail.setValue('test@gmail.com')
        await textPassword.setValue('testtest')

        await button.trigger("click");



        const wrapper = mount(Home)
    });
});