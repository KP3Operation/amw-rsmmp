/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import PrivacyPolicyPage from "./PrivacyPolicy.vue";

describe("PrivacyPolicyPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(PrivacyPolicyPage);
        expect(wrapper.exists()).toBe(true);
    });
});
