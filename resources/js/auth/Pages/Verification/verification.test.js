/**
 * @vitest-environment happy-dom
 */

import VerificationPage from "@auth/Pages/Verification/Verification.vue";
import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";

describe("VerificationPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(VerificationPage);
        expect(wrapper.exists()).toBe(true);
    });
});
