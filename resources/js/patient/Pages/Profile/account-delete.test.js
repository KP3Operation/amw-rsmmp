/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import AccountDeletePage from "./AccountDelete.vue";

describe("AccountDeletePage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(AccountDeletePage);
        expect(wrapper.exists()).toBe(true);
    });
});
