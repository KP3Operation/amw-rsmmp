/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import SubmotButtonComponent from "./SubmitButton.vue";

describe("SubmotButtonComponent", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(SubmotButtonComponent);
        expect(wrapper.exists()).toBe(true);
    });
});
