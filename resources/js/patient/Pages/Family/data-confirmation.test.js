/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import DataConfirmationPage from "./DataConfirmation.vue";

describe("DataConfirmationPage", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(DataConfirmationPage);
        expect(wrapper.exists()).toBe(true);
    });
});
