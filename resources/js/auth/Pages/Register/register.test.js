/**
 * @vitest-environment happy-dom
 */

import RegisterPage from "@auth/Pages/Register/Register.vue";
import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";

describe("RegisterPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(RegisterPage);
        expect(wrapper.exists()).toBe(true);
    });
});
