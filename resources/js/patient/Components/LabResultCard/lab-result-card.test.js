/**
 * @vitest-environment happy-dom
 */

import { mount } from "@vue/test-utils";
import { createPinia, setActivePinia } from "pinia";
import { beforeEach, describe, expect, it } from "vitest";
import LabResultCardComponent from "./LabResultCard.vue";

describe("LabResultCardComponent", () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });

    it("renders correctly", () => {
        const wrapper = mount(LabResultCardComponent);
        expect(wrapper.exists()).toBe(true);
    });
});
