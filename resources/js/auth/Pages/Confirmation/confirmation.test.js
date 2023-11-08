/**
 * @vitest-environment happy-dom
 */

import ConfirmationPage from "@auth/Pages/Confirmation/Confirmation.vue";
import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";

describe("ConfirmationPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(ConfirmationPage);
        expect(wrapper.exists()).toBe(true);
    });
});
