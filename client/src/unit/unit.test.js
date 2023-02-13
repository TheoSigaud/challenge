/**
 *@vitest-environment happy-dom
**/

import Login from "@/views/LoginView.vue";
import Register from "@/components/Register.vue";
import {mount} from "@vue/test-utils";
import {describe} from "vitest";

describe("Test Login Register", () => {
    it("login", async () => {
        const wrapperLogin = mount(Login)
        const textEmail = wrapperLogin.find('input[type="email"]')
        const textPassword = wrapperLogin.find('input[type="password"]')
        const button = wrapperLogin.find('#btn-login')

        await textEmail.setValue('test@gmail.com')
        await textPassword.setValue('testtest')

        await button.trigger("click");

        expect(wrapperLogin.find(".has-text-danger").exists()).toBe(false);

    });

    it("register", async () => {
        const wrapperLogin = mount(Register)
        const textLastname = wrapperLogin.find('#lastname"]')
        const textFirstname = wrapperLogin.find('#firstname"]')
        const textAddress = wrapperLogin.find('#address"]')
        const textConfirmPasswordRegister = wrapperLogin.find('#confirmPasswordRegister')
        const textPasswordRegister = wrapperLogin.find('#passwordRegister"]')
        const textEmail = wrapperLogin.find('input[type="email"]')
        const button = wrapperLogin.find('#btn-register')

        await textEmail.setValue('test@gmail.com')
        await textFirstname.setValue('toto')
        await textLastname.setValue('titi')
        await textAddress.setValue('11 boulevard')
        await textPasswordRegister.setValue('testtest')
        await textConfirmPasswordRegister.setValue('testtest')

        await button.trigger("click");

        expect(wrapperLogin.find(".has-text-danger").exists()).toBe(false);

    });
});