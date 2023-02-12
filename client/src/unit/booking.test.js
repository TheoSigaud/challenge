/**
 *@vitest-environment happy-dom
**/

import Home from "@/views/HomeView.vue";
import { mount } from "@vue/test-utils";
import {describe} from "vitest";

describe("Mon test login", () => {
    it("should render correct contents", () => {
        // restitue le composant
        const wrapper = mount(Home)

        expect(wrapper.find('.data'))

    });
});