/**
 * @vitest-environment happy-dom
 */

import LoginPage from "@auth/Pages/Login/Login.vue";
import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";

describe("LoginPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(LoginPage);
        expect(wrapper.exists()).toBe(true);
    });
});
